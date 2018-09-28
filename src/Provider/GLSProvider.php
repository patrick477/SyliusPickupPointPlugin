<?php

declare(strict_types=1);

namespace Setono\SyliusPickupPointPlugin\Provider;

use Setono\SyliusPickupPointPlugin\PickupPoint\PickupPoint;
use Sylius\Component\Core\Model\OrderInterface;

class GLSProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return 'gls';
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'GLS';
    }

    /**
     * @todo create a bundle with a soap client maybe using https://github.com/phpro/soap-client
     *
     * {@inheritdoc}
     */
    public function findPickupPoints(OrderInterface $order): array
    {
        if(null === $order->getShippingAddress()) {
            return [];
        }

        $client = new \SoapClient('http://www.gls.dk/webservices_v4/wsShopFinder.asmx?WSDL');
        $res = $client->SearchNearestParcelShops([
            'street' => $order->getShippingAddress()->getStreet(),
            'zipcode' => $order->getShippingAddress()->getPostcode(),
            'countryIso3166A2' => $order->getShippingAddress()->getCountryCode(),
            'Amount' => 10,
        ]);

        if(!($res instanceof \stdClass)
            || !isset($res->SearchNearestParcelShopsResult)
            || !isset($res->SearchNearestParcelShopsResult->parcelshops)
            || empty($res->SearchNearestParcelShopsResult->parcelshops->PakkeshopData)
        ) {
            return [];
        }

        $pickupPoints = [];
        foreach ($res->SearchNearestParcelShopsResult->parcelshops->PakkeshopData as $item) {
            $pickupPoints[] = new PickupPoint($item->Number, $item->CompanyName, $item->Streetname, $item->ZipCode, $item->CityName, $item->CountryCodeISO3166A2, $item->Latitude, $item->Longitude);
        }

        return $pickupPoints;
    }
}

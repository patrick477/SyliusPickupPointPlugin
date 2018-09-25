<?php

declare(strict_types=1);

namespace Setono\SyliusPickupPointPlugin\Form\Extension;

use Setono\SyliusPickupPointPlugin\Manager\ProviderManager;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ShippingMethodTypeExtension extends AbstractTypeExtension
{
    /**
     * @var ProviderManager
     */
    private $providerManager;

    public function __construct(ProviderManager $providerManager)
    {
        $this->providerManager = $providerManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pickupPointProvider', ChoiceType::class, [
            'placeholder' => 'setono_sylius_pickup_point.form.shipping_method.select_pickup_point_provider',
            'label' => 'setono_sylius_pickup_point.form.shipping_method.pickup_point_provider',
            'choices' => $this->getChoices(),
        ]);
    }

    public function getExtendedType(): string
    {
        return ShippingMethodType::class;
    }

    private function getChoices(): array
    {
        $choices = [];

        foreach ($this->providerManager->all() as $provider) {
            $choices[$provider->getName()] = get_class($provider);
        }

        return $choices;
    }
}

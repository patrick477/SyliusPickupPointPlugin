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
     * {@inheritdoc}
     */
    public function findPickupPoints(OrderInterface $order): array
    {
        return [
            new PickupPoint('id', 'Pickup Point #1', 'Address 1', '9000', 'Aalborg', 'Denmark', '123', '123'),
            new PickupPoint('id', 'Pickup Point #2', 'Address 123', '8000', 'Aarhus', 'Denmark', '123', '123')
        ];
    }
}

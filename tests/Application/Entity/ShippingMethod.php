<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusPickupPointPlugin\Application\Entity;

use Setono\SyliusPickupPointPlugin\Model\PickupPointProviderAwareInterface;
use Setono\SyliusPickupPointPlugin\Model\ShippingMethodTrait;
use Sylius\Component\Core\Model\ShippingMethod as BaseShippingMethod;

class ShippingMethod extends BaseShippingMethod implements PickupPointProviderAwareInterface
{
    use ShippingMethodTrait;
}

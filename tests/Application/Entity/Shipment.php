<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusPickupPointPlugin\Application\Entity;

use Setono\SyliusPickupPointPlugin\Model\PickupPointIdAwareInterface;
use Setono\SyliusPickupPointPlugin\Model\ShipmentTrait;
use Sylius\Component\Core\Model\Shipment as BaseShipment;

class Shipment extends BaseShipment implements PickupPointIdAwareInterface
{
    use ShipmentTrait;
}

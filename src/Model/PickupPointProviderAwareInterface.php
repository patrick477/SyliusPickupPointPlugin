<?php

declare(strict_types=1);

namespace Setono\SyliusPickupPointPlugin\Model;

interface PickupPointProviderAwareInterface
{
    /**
     * Returns true if this object has an associated pickup provider
     *
     * @return bool
     */
    public function hasPickupPointProvider(): bool;

    /**
     * Returns the class name of the pickup provider
     *
     * @return string
     */
    public function getPickupPointProvider(): string;
}

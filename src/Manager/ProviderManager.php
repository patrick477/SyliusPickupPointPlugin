<?php

declare(strict_types=1);

namespace Setono\SyliusPickupPointPlugin\Manager;

use Setono\SyliusPickupPointPlugin\Provider\ProviderInterface;

class ProviderManager
{
    /**
     * @var ProviderInterface[]
     */
    private $providers;

    public function __construct()
    {
        $this->providers = [];
    }

    public function addProvider(ProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    public function all(): array
    {
        return $this->providers;
    }

    public function findByClassName(string $class): ?ProviderInterface
    {
        foreach ($this->providers as $provider) {
            if (get_class($provider) === $class) {
                return $provider;
            }
        }

        return null;
    }
}

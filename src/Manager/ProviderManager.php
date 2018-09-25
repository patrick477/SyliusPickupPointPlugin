<?php

declare(strict_types=1);

namespace Setono\SyliusPickupPointPlugin\Manager;

use Setono\SyliusPickupPointPlugin\Exception\NonUniqueProviderCodeException;
use Setono\SyliusPickupPointPlugin\Provider\ProviderInterface;

/**
 * @todo create an interface
 */
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

    /**
     * @param ProviderInterface $provider
     */
    public function addProvider(ProviderInterface $provider): void
    {
        // check for uniqueness of code
        foreach ($this->providers as $item) {
            if ($provider->getCode() === $item->getCode()) {
                throw NonUniqueProviderCodeException::create($provider);
            }
        }

        $this->providers[] = $provider;
    }

    /**
     * @return ProviderInterface[]
     */
    public function all(): array
    {
        return $this->providers;
    }

    /**
     * @param string $class
     *
     * @return ProviderInterface|null
     */
    public function findByClassName(string $class): ?ProviderInterface
    {
        foreach ($this->providers as $provider) {
            if (get_class($provider) === $class) {
                return $provider;
            }
        }

        return null;
    }

    public function findByCode(string $code): ?ProviderInterface
    {
        foreach ($this->providers as $provider) {
            if($provider->getCode() === $code) {
                return $provider;
            }
        }

        return null;
    }
}

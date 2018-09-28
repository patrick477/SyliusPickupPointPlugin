<?php

declare(strict_types=1);

namespace Setono\SyliusPickupPointPlugin\Form\Extension;

use Setono\SyliusPickupPointPlugin\Manager\ProviderManager;
use Setono\SyliusPickupPointPlugin\Model\PickupPointProviderAwareInterface;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodChoiceType;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ShippingMethodChoiceTypeExtension extends AbstractTypeExtension
{
    /**
     * @var ProviderManager
     */
    private $providerManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CartContextInterface
     */
    private $cartContext;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    public function __construct(ProviderManager $providerManager, RouterInterface $router, CartContextInterface $cartContext, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->providerManager = $providerManager;
        $this->router = $router;
        $this->cartContext = $cartContext;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $defaultAttr = ['class' => 'input-shipping-method'];

        $resolver->setDefault('choice_attr', function (PickupPointProviderAwareInterface $choiceValue, $key, $value) use ($defaultAttr) {
            if ($choiceValue->hasPickupPointProvider()) {
                $provider = $this->providerManager->findByClassName($choiceValue->getPickupPointProvider());
                if (!$provider) {
                    return $defaultAttr;
                }

                return [
                        'data-pickup-point-provider' => $provider->getCode(),
                        'data-pickup-point-provider-url' => $this->router->generate('setono_sylius_pickup_point_plugin_shop_ajax_find_pickup_points', ['provider' => $provider->getCode()]),
                        'data-csrf-token' => $this->csrfTokenManager->getToken((string)$this->cartContext->getCart()->getId())
                    ] + $defaultAttr;
            }

            return $defaultAttr;
        });
    }

    public function getExtendedType(): string
    {
        return ShippingMethodChoiceType::class;
    }
}

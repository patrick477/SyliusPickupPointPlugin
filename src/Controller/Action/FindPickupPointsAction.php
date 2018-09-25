<?php

declare(strict_types=1);

namespace Setono\SyliusPickupPointPlugin\Controller\Action;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Setono\SyliusPickupPointPlugin\Manager\ProviderManager;
use Setono\SyliusPickupPointPlugin\Model\PickupPointProviderAwareInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\ShippingMethodRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class FindPickupPointsAction
{
    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    /**
     * @var CartContextInterface
     */
    private $cartContext;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var ProviderManager
     */
    private $providerManager;

    /**
     * @var ShippingMethodRepositoryInterface
     */
    private $shippingMethodRepository;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        CartContextInterface $cartContext,
        CsrfTokenManagerInterface $csrfTokenManager,
        ProviderManager $providerManager,
        ShippingMethodRepositoryInterface $shippingMethodRepository
    ) {
        $this->viewHandler = $viewHandler;
        $this->cartContext = $cartContext;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->providerManager = $providerManager;
        $this->shippingMethodRepository = $shippingMethodRepository;
    }

    public function __invoke(Request $request, string $provider): Response
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

        if (!$this->isCsrfTokenValid((string) $order->getId(), $request->get('_csrf_token'))) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid CSRF token.');
        }

//        $shippingMethodCode = $request->request->getInt('shippingMethodCode');
//        if ($shippingMethodCode <= 0) {
//            throw new NotFoundHttpException();
//        }
//
//        /** @var PickupPointProviderAwareInterface|null $shippingMethod */
//        $shippingMethod = $this->shippingMethodRepository->findOneBy([
//            'code' => $shippingMethodCode,
//        ]);
//        if (null === $shippingMethod || !($shippingMethod instanceof PickupPointProviderAwareInterface) || !$shippingMethod->hasPickupPointProvider()) {
//            throw new NotFoundHttpException();
//        }

        $provider = $this->providerManager->findByCode($provider);
        if (null === $provider) {
            throw new NotFoundHttpException();
        }

        $pickupPoints = $provider->findPickupPoints($order);

        return $this->viewHandler->handle(View::create($pickupPoints));
    }

    private function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($id, $token));
    }
}

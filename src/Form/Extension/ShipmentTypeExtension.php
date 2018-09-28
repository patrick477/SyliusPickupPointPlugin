<?php

declare(strict_types=1);

namespace Setono\SyliusPickupPointPlugin\Form\Extension;

use Sylius\Bundle\CoreBundle\Form\Type\Checkout\ShipmentType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class ShipmentTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pickupPointId', HiddenType::class, [
            'attr' => [
                'class' => 'pickup-point-id'
            ]
        ]);
    }

    public function getExtendedType(): string
    {
        return ShipmentType::class;
    }
}

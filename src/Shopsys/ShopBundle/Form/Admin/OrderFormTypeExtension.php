<?php

namespace Shopsys\ShopBundle\Form\Admin;

use Shopsys\FrameworkBundle\Form\Admin\Order\OrderFormType;
use Shopsys\FrameworkBundle\Form\DisplayOnlyType;
use Shopsys\FrameworkBundle\Form\GroupType;
use Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class OrderFormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \Shopsys\ShopBundle\Model\Order\Order $order */
        $order = $options['order'];

        if ($order->getPickUpPlace() !== null) {
            // Place pickup place group before noteGroup and orderItems
            $noteGroup = $builder->get('noteGroup');
            $builder->remove('noteGroup');
            $itemsGroup = $builder->get('orderItems');
            $builder->remove('orderItems');

            $builder->add($this->createPickupPlaceGroup($builder, $order->getPickUpPlace()));

            $builder->add($noteGroup);
            $builder->add($itemsGroup);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace $pickUpPlace
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    private function createPickupPlaceGroup(FormBuilderInterface $formBuilder, PickUpPlace $pickUpPlace): FormBuilderInterface
    {
        $pickupPlaceGroup = $formBuilder->create('pickupPlaceGroup', GroupType::class, [
            'label' => t('Zasilkovna.cz - dispensing point'),
        ]);

        $pickupPlaceGroup
            ->add('id', DisplayOnlyType::class, [
                'label' => t('Id'),
                'data' => $pickUpPlace->getId(),
            ])
            ->add('name', DisplayOnlyType::class, [
                'label' => t('Name'),
                'data' => $pickUpPlace->getName(),
            ])
            ->add('address', DisplayOnlyType::class, [
                'label' => t('Address'),
                'data' => $pickUpPlace->getFullAddress(),
            ])
            ->add('description', DisplayOnlyType::class, [
                'label' => t('Description'),
                'data' => $pickUpPlace->getDescription(),
            ]);

        return $pickupPlaceGroup;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return OrderFormType::class;
    }
}

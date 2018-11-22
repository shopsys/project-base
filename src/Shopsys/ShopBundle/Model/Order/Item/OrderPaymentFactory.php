<?php

namespace Shopsys\ShopBundle\Model\Order\Item;

use Shopsys\FrameworkBundle\Component\EntityExtension\EntityNameResolver;
use Shopsys\FrameworkBundle\Model\Order\Order;
use Shopsys\FrameworkBundle\Model\Payment\Payment;
use Shopsys\FrameworkBundle\Model\Pricing\Price;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderPayment as BaseOrderPayment;

use Shopsys\FrameworkBundle\Model\Order\Item\OrderPaymentFactory as BaseOrderPaymentFactory;

class OrderPaymentFactory extends BaseOrderPaymentFactory
{

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Order $order
     * @param string $name
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Price $price
     * @param string $vatPercent
     * @param int $quantity
     * @param \Shopsys\FrameworkBundle\Model\Payment\Payment $payment
     * @param string|null $catnum
     * @return \Shopsys\FrameworkBundle\Model\Order\Item\OrderPayment
     */
    public function create(
        Order $order,
        string $name,
        Price $price,
        string $vatPercent,
        int $quantity,
        Payment $payment
    ): BaseOrderPayment {
        $classData = $this->entityNameResolver->resolve(BaseOrderPayment::class);

        return new $classData(
            $order,
            $name,
            $price,
            $vatPercent,
            $quantity,
            $payment,
            $payment->getPohodaCatnum()
        );
    }
}

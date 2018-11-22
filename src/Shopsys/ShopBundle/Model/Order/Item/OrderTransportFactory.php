<?php

namespace Shopsys\ShopBundle\Model\Order\Item;

use Shopsys\FrameworkBundle\Component\EntityExtension\EntityNameResolver;
use Shopsys\FrameworkBundle\Model\Order\Order;
use Shopsys\FrameworkBundle\Model\Pricing\Price;
use Shopsys\FrameworkBundle\Model\Transport\Transport;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderTransport as BaseOrderTransport;

use Shopsys\FrameworkBundle\Model\Order\Item\OrderTransportFactory as BaseOrderTransportFactory;

class OrderTransportFactory extends BaseOrderTransportFactory
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Order $order
     * @param string $name
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Price $price
     * @param string $vatPercent
     * @param int $quantity
     * @param \Shopsys\FrameworkBundle\Model\Transport\Transport $transport
     * @param string|null $catnum
     * @return \Shopsys\FrameworkBundle\Model\Order\Item\OrderTransport
     */
    public function create(
        Order $order,
        string $name,
        Price $price,
        string $vatPercent,
        int $quantity,
        Transport $transport
    ): BaseOrderTransport {
        $classData = $this->entityNameResolver->resolve(BaseOrderTransport::class);

        return new $classData(
            $order,
            $name,
            $price,
            $vatPercent,
            $quantity,
            $transport,
            $transport->getPohodaCatnum()
        );
    }
}

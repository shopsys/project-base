<?php

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\OrderData as BaseOrderData;

class OrderData extends BaseOrderData
{
    /**
     * @var \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace|null
     */
    public $pickUpPlace;

    public function __construct()
    {
        parent::__construct();
        $this->pickUpPlace = null;
    }
}

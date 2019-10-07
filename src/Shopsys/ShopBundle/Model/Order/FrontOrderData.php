<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\FrontOrderData as BaseFrontOrderData;
use Shopsys\ShopBundle\Model\Company\Company;

/**
 * @property \Shopsys\ShopBundle\Model\Transport\Transport|null $transport
 * @property \Shopsys\ShopBundle\Model\Payment\Payment|null $payment
 * @property \Shopsys\ShopBundle\Model\Order\Item\OrderItemData[] $itemsWithoutTransportAndPayment
 * @property \Shopsys\ShopBundle\Model\Administrator\Administrator|null $createdAsAdministrator
 * @property \Shopsys\ShopBundle\Model\Order\Item\OrderItemData|null $orderPayment
 * @property \Shopsys\ShopBundle\Model\Order\Item\OrderItemData|null $orderTransport
 * @method \Shopsys\ShopBundle\Model\Order\Item\OrderItemData[] getNewItemsWithoutTransportAndPayment()
 */
class FrontOrderData extends BaseFrontOrderData
{
    /**
     * @var Company|null
     */
    public $company;

    public function __construct()
    {
        parent::__construct();
        $this->company = null;
    }
}

<?php

declare(strict_types=1);

namespace App\FrontendApi\Model\Payment\Exception;

use Exception;
use Shopsys\FrameworkBundle\Model\Pricing\Price;

class PaymentPriceChangedException extends Exception
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Price $currentPaymentPrice
     */
    public function __construct(private Price $currentPaymentPrice)
    {
        parent::__construct();
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Pricing\Price
     */
    public function getCurrentPaymentPrice(): Price
    {
        return $this->currentPaymentPrice;
    }
}

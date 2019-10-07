<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Company;

class CompanyData
{
    /**
     * @var \Shopsys\ShopBundle\Model\Customer\User[]
     */
    public $users;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\DeliveryAddress[]
     */
    public $deliveryAddresses;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\BillingAddress|null
     */
    public $billingAddress;

    public function __construct()
    {
        $this->users = [];
        $this->deliveryAddresses = [];
        $this->billingAddress = null;
    }
}

<?php

declare(strict_types=1);

namespace App\Model\Customer\User;

use Shopsys\FrameworkBundle\Model\Customer\Customer;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUser as BaseUser;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserData as BaseUserData;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserDataFactory as BaseUserDataFactory;

/**
 * @method fillForDomainId(\App\Model\Customer\User\CustomerUserData $customerUserData, int $domainId)
 * @method fillFromUser(\App\Model\Customer\User\CustomerUserData $customerUserData, \App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Customer\User\CustomerUserData create()
 * @method \App\Model\Customer\User\CustomerUserData createForCustomerWithPresetPricingGroup(\Shopsys\FrameworkBundle\Model\Customer\Customer $customer)
 */
class CustomerUserDataFactory extends BaseUserDataFactory
{
    /**
     * @return \App\Model\Customer\User\CustomerUserData
     */
    protected function createInstance(): BaseUserData
    {
        return new CustomerUserData();
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Customer\Customer $customer
     * @return \App\Model\Customer\User\CustomerUserData
     */
    public function createForCustomer(Customer $customer): BaseUserData
    {
        $customerUserData = $this->create();
        $customerUserData->customer = $customer;

        return $customerUserData;
    }

    /**
     * @param int $domainId
     * @return \App\Model\Customer\User\CustomerUserData
     */
    public function createForDomainId(int $domainId): BaseUserData
    {
        $customerUserData = $this->create();
        $this->fillForDomainId($customerUserData, $domainId);

        return $customerUserData;
    }

    /**
     * @param \App\Model\Customer\User\CustomerUser $customerUser
     * @return \App\Model\Customer\User\CustomerUserData
     */
    public function createFromCustomerUser(BaseUser $customerUser): BaseUserData
    {
        $customerUserData = $this->create();
        $this->fillFromUser($customerUserData, $customerUser);

        return $customerUserData;
    }
}

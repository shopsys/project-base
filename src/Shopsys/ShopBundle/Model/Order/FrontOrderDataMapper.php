<?php

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Customer\User;
use Shopsys\FrameworkBundle\Model\Order\FrontOrderDataMapper as BaseFrontOrderDataMapper;
use Shopsys\FrameworkBundle\Model\Order\FrontOrderData as BaseFrontOrderData;

class FrontOrderDataMapper extends BaseFrontOrderDataMapper
{
    /**
     * @param \Shopsys\ShopBundle\Model\Order\FrontOrderData $frontOrderData
     * @param \Shopsys\ShopBundle\Model\Customer\User $user
     */
    protected function prefillFrontFormDataFromCustomer(BaseFrontOrderData $frontOrderData, User $user)
    {
        parent::prefillFrontFormDataFromCustomer($frontOrderData, $user);
        $frontOrderData->company = $user->getCompany();
    }
}

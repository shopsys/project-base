<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer;

use Shopsys\FrameworkBundle\Model\Customer\UserData as BaseUserData;

class UserData extends BaseUserData
{
    /**
     * @var \Shopsys\ShopBundle\Model\Company\Company|null
     */
    public $company;

    public function __construct()
    {
        parent::__construct();
        $this->company = null;
    }
}

<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator;

use Shopsys\FrameworkBundle\Model\Administrator\AdministratorData as BaseAdministratorData;

class AdministratorData extends BaseAdministratorData
{
    public $roles;

    public function __construct()
    {
        parent::__construct();

        $this->roles = [];
    }
}

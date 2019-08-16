<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Security;

use Shopsys\FrameworkBundle\Model\Security\Roles as BaseRoles;

class Roles extends BaseRoles
{
    public const ROLE_VIEW_PRODUCTS = 'ROLE_VIEW_PRODUCTS';
    public const ROLE_VIEW_MAIL_TEMPLATES = 'ROLE_VIEW_MAIL_TEMPLATES';
}

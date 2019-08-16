<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator;

use Shopsys\ShopBundle\Model\Security\Roles;

class AdministratorRolesFacade
{
    /**
     * @return array
     */
    public function getAllRolesIndexedByTitles(): array
    {
        return [
            t('See products') => Roles::ROLE_VIEW_PRODUCTS,
            t('See mail templates') => Roles::ROLE_VIEW_MAIL_TEMPLATES,
        ];
    }
}

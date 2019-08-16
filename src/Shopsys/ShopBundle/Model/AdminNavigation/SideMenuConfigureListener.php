<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\AdminNavigation;

use Knp\Menu\ItemInterface;
use Shopsys\FrameworkBundle\Model\AdminNavigation\ConfigureMenuEvent;
use Shopsys\ShopBundle\Model\Security\Roles;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SideMenuConfigureListener
{
    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\AdminNavigation\ConfigureMenuEvent $event
     */
    public function onRootConfigure(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();

        $this->removeItemFromMenuIfPrivilegeIsNotGranted($menu, 'products', Roles::ROLE_VIEW_PRODUCTS);
        $this->removeItemFromMenuIfPrivilegeIsNotGranted($menu, 'mail_templates', Roles::ROLE_VIEW_MAIL_TEMPLATES);
    }

    /**
     * @param \Knp\Menu\ItemInterface $menu
     * @param string $itemName
     * @param string $privilege
     */
    private function removeItemFromMenuIfPrivilegeIsNotGranted(ItemInterface $menu, string $itemName, string $privilege): void
    {
        if ($this->authorizationChecker->isGranted($privilege) === false) {
            $menu->removeChild($itemName);
        }
    }
}

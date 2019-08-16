<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Administrator;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Administrator\Administrator as BaseAdministrator;
use Shopsys\FrameworkBundle\Model\Administrator\AdministratorData as BaseAdministratorData;

/**
 * @ORM\Entity
 * @ORM\Table(
 *   name="administrators",
 *   indexes={
 *     @ORM\Index(columns={"username"})
 *   }
 * )
 */
class Administrator extends BaseAdministrator
{
    /**
     * @var string[]
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles;

    /**
     * @param \Shopsys\ShopBundle\Model\Administrator\AdministratorData $administratorData
     */
    public function __construct(BaseAdministratorData $administratorData)
    {
        parent::__construct($administratorData);

        $this->roles = $administratorData->roles;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Administrator\AdministratorData $administratorData
     */
    public function edit(BaseAdministratorData $administratorData): void
    {
        parent::edit($administratorData);

        $this->roles = $administratorData->roles;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = parent::getRoles();

        if ($this->roles !== null) {
            return array_merge($roles, $this->roles);
        } else {
            return $roles;
        }
    }
}

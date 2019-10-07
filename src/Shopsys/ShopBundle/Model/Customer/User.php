<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress;
use Shopsys\FrameworkBundle\Model\Customer\User as BaseUser;
use Shopsys\FrameworkBundle\Model\Customer\UserData as BaseUserData;
use Shopsys\ShopBundle\Model\Company\Company;

/**
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="email_domain", columns={"email", "domain_id"})
 *     },
 *     indexes={
 *         @ORM\Index(columns={"email"})
 *     }
 * )
 * @ORM\Entity
 * @property \Shopsys\ShopBundle\Model\Customer\DeliveryAddress|null $deliveryAddress
 * @method \Shopsys\ShopBundle\Model\Customer\DeliveryAddress|null getDeliveryAddress()
 * @method setDeliveryAddress(\Shopsys\ShopBundle\Model\Customer\DeliveryAddress|null $deliveryAddress)
 */
class User extends BaseUser
{
    /**
     * @var \Shopsys\ShopBundle\Model\Company\Company
     * @ORM\ManyToOne(targetEntity="Shopsys\ShopBundle\Model\Company\Company", inversedBy="users")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=true)
     */
    private $company;

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\UserData $userData
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddress $billingAddress
     * @param \Shopsys\ShopBundle\Model\Customer\DeliveryAddress|null $deliveryAddress
     */
    public function __construct(
        BaseUserData $userData,
        BillingAddress $billingAddress,
        ?DeliveryAddress $deliveryAddress
    ) {
        parent::__construct($userData, $billingAddress, $deliveryAddress);
        $this->company = $userData->company;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Customer\UserData $userData
     */
    public function edit(BaseUserData $userData)
    {
        parent::edit($userData);
        $this->company = $userData->company;
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Company\Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }
}

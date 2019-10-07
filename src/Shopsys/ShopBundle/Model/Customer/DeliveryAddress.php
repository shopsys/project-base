<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Customer;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress as BaseDeliveryAddress;
use Shopsys\ShopBundle\Model\Company\Company;

/**
 * @ORM\Table(name="delivery_addresses")
 * @ORM\Entity
 */
class DeliveryAddress extends BaseDeliveryAddress
{
    /**
     * @var \Shopsys\ShopBundle\Model\Company\Company
     * @ORM\ManyToOne(targetEntity="Shopsys\ShopBundle\Model\Company\Company", inversedBy="deliveryAddresses")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=true)
     */
    private $company;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Company\Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Company\Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }
}

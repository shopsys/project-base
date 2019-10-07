<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Company;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress;

/**
 * @ORM\Table(name="companies")
 * @ORM\Entity
 */
class Company
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\User[]|\Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(
     *     targetEntity="Shopsys\FrameworkBundle\Model\Customer\User",
     *     mappedBy="company",
     *     cascade={"remove"}
     * )
     */
    private $users;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\DeliveryAddress[]|\Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(
     *     targetEntity="Shopsys\FrameworkBundle\Model\Customer\DeliveryAddress",
     *     mappedBy="company",
     *     cascade={"remove"}
     * )
     */
    private $deliveryAddresses;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\BillingAddress
     * @ORM\OneToOne(targetEntity="Shopsys\FrameworkBundle\Model\Customer\BillingAddress", cascade={"persist"})
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id", nullable=false)
     */
    private $billingAddress;

    /**
     * @param \Shopsys\ShopBundle\Model\Company\CompanyData $companyData
     */
    public function __construct(CompanyData $companyData)
    {
        $this->users = $companyData->users;
        $this->deliveryAddresses = $companyData->deliveryAddresses;
        $this->billingAddress = $companyData->billingAddress;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Company\CompanyData $companyData
     */
    public function edit(CompanyData $companyData)
    {
        $this->users = $companyData->users;
        $this->deliveryAddresses = $companyData->deliveryAddresses;
        $this->billingAddress = $companyData->billingAddress;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Customer\User[]
     */
    public function getUsers(): array
    {
        return $this->users->toArray();
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Customer\DeliveryAddress[]
     */
    public function getDeliveryAddresses(): array
    {
        return $this->deliveryAddresses->toArray();
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Customer\BillingAddress
     */
    public function getBillingAddress(): BillingAddress
    {
        return $this->billingAddress;
    }
}

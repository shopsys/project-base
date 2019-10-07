<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\ShopBundle\Model\Company\CompanyData;
use Shopsys\ShopBundle\Model\Company\CompanyDataFactory;
use Shopsys\ShopBundle\Model\Company\CompanyFacade;
use Shopsys\ShopBundle\Model\Customer\User;

class CompanyDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface
{
    public const DEFAULT_COMPANY = 'company_default';

    /**
     * @var \Shopsys\ShopBundle\Model\Company\CompanyFacade
     */
    protected $companyFacade;

    /**
     * @var \Shopsys\ShopBundle\Model\Company\CompanyDataFactory
     */
    protected $companyDataFactory;

    /**
     * @param \Shopsys\ShopBundle\Model\Company\CompanyFacade $companyFacade
     * @param \Shopsys\ShopBundle\Model\Company\CompanyDataFactory $companyDataFactory
     */
    public function __construct(CompanyFacade $companyFacade, CompanyDataFactory $companyDataFactory)
    {
        $this->companyFacade = $companyFacade;
        $this->companyDataFactory = $companyDataFactory;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $companyData = $this->companyDataFactory->create();
        /** @var $user \Shopsys\ShopBundle\Model\Customer\User */
        $user = $this->getReference(UserDataFixture::USER_WITH_RESET_PASSWORD_HASH);

        $companyData->billingAddress = $user->getBillingAddress();
        $companyData->users = [$user];
        $companyData->deliveryAddresses = $user->getDeliveryAddress() === null ? [] : [$user->getDeliveryAddress()];

        $this->createCompany($companyData, self::DEFAULT_COMPANY);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Company\CompanyData $companyData
     * @param string $referenceName
     */
    protected function createCompany(CompanyData $companyData, $referenceName): void
    {
        $company = $this->companyFacade->create($companyData);
        $this->addReference($referenceName, $company);
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            UserDataFixture::class,
        ];
    }
}

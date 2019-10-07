<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Company;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormData;
use Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactory;
use Shopsys\FrameworkBundle\Model\Customer\CustomerFacade;
use Shopsys\ShopBundle\Model\Customer\DeliveryAddressRepository;
use Shopsys\ShopBundle\Model\Customer\UserData;

class CompanyFacade
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var \Shopsys\ShopBundle\Model\Company\CompanyRepository
     */
    private $companyRepository;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerFacade
     */
    private $customerFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactory
     */
    private $customerDataFactory;

    /**
     * @var \Shopsys\ShopBundle\Model\Customer\DeliveryAddressRepository
     */
    private $deliveryAddressRepository;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Shopsys\ShopBundle\Model\Company\CompanyRepository $companyRepository
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerFacade $customerFacade
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerDataFactory $customerDataFactory
     * @param \Shopsys\ShopBundle\Model\Customer\DeliveryAddressRepository $deliveryAddressRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        CompanyRepository $companyRepository,
        CustomerFacade $customerFacade,
        CustomerDataFactory $customerDataFactory,
        DeliveryAddressRepository $deliveryAddressRepository
    ) {
        $this->em = $em;
        $this->companyRepository = $companyRepository;
        $this->customerFacade = $customerFacade;
        $this->customerDataFactory = $customerDataFactory;
        $this->deliveryAddressRepository = $deliveryAddressRepository;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Company\CompanyData $companyData
     * @return \Shopsys\ShopBundle\Model\Company\Company
     */
    public function create(CompanyData $companyData): Company
    {
        $company = new Company($companyData);
        $this->em->persist($company);
        $this->em->flush($company);

        return $company;
    }

    /**
     * @param int $domainId
     * @param \Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormData $quickSearchData
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCompanyListQueryBuilderByQuickSearchData(
        $domainId,
        QuickSearchFormData $quickSearchData
    ) {
        return $this->companyRepository->getCompanyListQueryBuilderByQuickSearchData(
            $domainId,
            $quickSearchData
        );
    }

    /**
     * @param int $id
     * @return \Shopsys\ShopBundle\Model\Company\Company|null
     */
    public function getById(int $id)
    {
        return $this->companyRepository->getById($id);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Company\CompanyData $companyData
     * @param \Shopsys\ShopBundle\Model\Company\Company $company
     */
    public function edit(CompanyData $companyData, Company $company)
    {
        $users = [];
        $billingAddress = null;
        foreach ($companyData->users as $id => $userData) {
            /** @var $userData \Shopsys\ShopBundle\Model\Customer\UserData */
            $user = $this->customerFacade->getUserById($id);
            $customerData = $this->customerDataFactory->createFromUser($user);
            $userData->company = $company;
            $customerData->userData = $userData;
            $customerData->billingAddressData = $companyData->billingAddress;
            $customerData->deliveryAddressData = reset($companyData->deliveryAddresses);
            $this->customerFacade->editByAdmin($id, $customerData);
            $billingAddress = $user->getBillingAddress();
            $users[] = $user;
        }

        $deliveryAddresses = [];
        foreach ($companyData->deliveryAddresses as $id => $deliveryAddressData) {
            $deliveryAddress = $this->deliveryAddressRepository->getById($id);
            $deliveryAddress->edit($deliveryAddressData);
            $deliveryAddress->setCompany($company);
            $this->em->flush($deliveryAddress);
            $deliveryAddresses[] = $deliveryAddress;
        }

        $companyData->users = $users;
        $companyData->deliveryAddresses = $deliveryAddresses;
        $companyData->billingAddress = $billingAddress;

        $company->edit($companyData);
    }
}

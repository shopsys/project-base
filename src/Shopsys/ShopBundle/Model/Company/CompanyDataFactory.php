<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Company;

class CompanyDataFactory
{
    /**
     * @return \Shopsys\ShopBundle\Model\Company\CompanyData
     */
    public function create(): CompanyData
    {
        return new CompanyData();
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Company\Company $company
     * @return \Shopsys\ShopBundle\Model\Company\CompanyData
     */
    public function createFromCompany(Company $company): CompanyData
    {
        $companyData = $this->create();
        $companyData->deliveryAddresses = $company->getDeliveryAddresses();
        $companyData->users = $company->getUsers();
        $companyData->billingAddress = $company->getBillingAddress();

        return $companyData;
    }
}

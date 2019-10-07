<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Company;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Shopsys\FrameworkBundle\Form\Admin\QuickSearch\QuickSearchFormData;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddress;

class CompanyRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getUserRepository()
    {
        return $this->em->getRepository(Company::class);
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
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('c.id, ba.companyName, COUNT(u.id) as userCount, COUNT(da.id) as deliveryAddressCount')
            ->from(Company::class, 'c')
            ->join(BillingAddress::class, 'ba', Join::WITH, 'c.billingAddress = ba.id')
            ->leftJoin('c.users', 'u', Join::WITH, 'u.domainId = :domainId')
            ->leftJoin('c.deliveryAddresses', 'da')
            ->setParameter('domainId', $domainId)
            ->addGroupBy('c.id, ba.companyName');
        return $queryBuilder;
    }

    /**
     * @param int $id
     * @return \Shopsys\ShopBundle\Model\Company\Company|null
     */
    public function getById(int $id)
    {
        return $this->getUserRepository()->find($id);
    }
}

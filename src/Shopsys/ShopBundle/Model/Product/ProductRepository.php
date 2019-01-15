<?php

declare(strict_types = 1);

namespace Shopsys\ShopBundle\Model\Product;

use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup;
use Shopsys\FrameworkBundle\Model\Product\ProductRepository as BaseProductRepository;

class ProductRepository extends BaseProductRepository
{
    /**
     * @param int $domainId
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroup $pricingGroup
     * @param array $productIds
     * @return \Shopsys\ShopBundle\Model\Product\Product[]
     */
    public function getVisibleByIds(int $domainId, PricingGroup $pricingGroup, array $productIds)
    {
        if (count($productIds) === 0) {
            return [];
        }

        $queryBuilder = $this->getAllVisibleQueryBuilder($domainId, $pricingGroup);
        $queryBuilder->andWhere('p.id IN (:productIds)')->setParameter('productIds', $productIds);

        return $queryBuilder->getQuery()->execute();
    }
}

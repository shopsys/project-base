<?php

declare(strict_types = 1);

namespace Shopsys\ShopBundle\Model\Product;

use Shopsys\FrameworkBundle\Model\Product\ProductOnCurrentDomainFacade as BaseProductOnCurrentDomainFacade;

class ProductOnCurrentDomainFacade extends BaseProductOnCurrentDomainFacade
{
    /**
     * @param array $productsIds
     */
    public function getVisibleProductsByIds(array $productsIds)
    {
        return $this->productRepository->getVisibleByIds(
            $this->domain->getId(),
            $this->currentCustomer->getPricingGroup(),
            $productsIds
        );
    }
}

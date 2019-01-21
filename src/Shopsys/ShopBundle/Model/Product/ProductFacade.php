<?php

namespace Shopsys\ShopBundle\Model\Product;

use Shopsys\FrameworkBundle\Model\Product\ProductFacade as BaseProductFacade;

class ProductFacade extends BaseProductFacade
{
    /**
     * @param int $extId
     */
    public function findByExtId(int $extId)
    {
        return $this->productRepository->findByExtId($extId);
    }
}

<?php

namespace Shopsys\ShopBundle\Model\Product;

use Shopsys\FrameworkBundle\Model\Product\ProductRepository as BaseProductRepository;

class ProductRepository extends BaseProductRepository
{
    /**
     * @param int $extId
     * @return \Shopsys\ShopBundle\Model\Product\Product|null
     */
    public function findByExtId(int $extId)
    {
        return $this->$this->getProductRepository()->findOneBy(['extId' => $extId]);
    }
}

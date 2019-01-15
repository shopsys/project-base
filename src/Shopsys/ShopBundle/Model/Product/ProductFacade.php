<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Product;

use Shopsys\FrameworkBundle\Model\Product\ProductFacade as BaseProductFacade;

class ProductFacade extends BaseProductFacade
{
    /**
     * @var \Shopsys\ShopBundle\Model\Product\ProductRepository
     */
    protected $productRepository;

    /**
     * @param int $extId
     * @return \Shopsys\ShopBundle\Model\Product\Product|null
     */
    public function findByExternalId(int $extId): ?Product
    {
        return $this->productRepository->findByExternalId($extId);
    }
}

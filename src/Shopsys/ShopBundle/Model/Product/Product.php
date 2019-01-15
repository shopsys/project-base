<?php

namespace Shopsys\ShopBundle\Model\Product;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Product\Product as BaseProduct;
use Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface;
use Shopsys\FrameworkBundle\Model\Product\ProductData as BaseProductData;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity
 */
class Product extends BaseProduct
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $extId;

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     * @param \Shopsys\ShopBundle\Model\Product\Product[]|null $variants
     */
    protected function __construct(BaseProductData $productData, array $variants = null)
    {
        $this->extId = $productData->extId ?? 0;
        parent::__construct($productData, $variants);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface $productCategoryDomainFactory
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     */
    public function edit(
        ProductCategoryDomainFactoryInterface $productCategoryDomainFactory,
        BaseProductData $productData
    ) {
        $this->extId = $productData->extId;
        parent::edit($productCategoryDomainFactory, $productData);
    }

    /**
     * @return int
     */
    public function getExtId(): int
    {
        return $this->extId;
    }
}

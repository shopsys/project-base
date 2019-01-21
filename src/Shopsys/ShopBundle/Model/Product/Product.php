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
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $extId;

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     * @param \Shopsys\ShopBundle\Model\Product\Product[]|null $variants
     */
    protected function __construct(BaseProductData $productData, array $variants = null)
    {
        parent::__construct($productData, $variants);
        $this->extId = $productData->extId;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomainFactoryInterface $productCategoryDomainFactory
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     */
    public function edit(
        ProductCategoryDomainFactoryInterface $productCategoryDomainFactory,
        BaseProductData $productData
    ) {
        parent::edit($productCategoryDomainFactory, $productData);
        $this->extId = $productData->extId;
    }

    /**
     * @return int
     */
    public function getExtId()
    {
        return $this->extId;
    }
}

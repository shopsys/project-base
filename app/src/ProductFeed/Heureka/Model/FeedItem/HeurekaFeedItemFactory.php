<?php

declare(strict_types=1);

namespace App\ProductFeed\Heureka\Model\FeedItem;

use App\Model\Product\Availability\ProductAvailabilityFacade;
use Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig;
use Shopsys\FrameworkBundle\Model\Category\CategoryFacade;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculationForCustomerUser;
use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\ProductFeed\HeurekaBundle\Model\FeedItem\HeurekaFeedItem;
use Shopsys\ProductFeed\HeurekaBundle\Model\FeedItem\HeurekaFeedItemFactory as BaseHeurekaFeedItemFactory;
use Shopsys\ProductFeed\HeurekaBundle\Model\FeedItem\HeurekaProductDataBatchLoader;
use Shopsys\ProductFeed\HeurekaBundle\Model\HeurekaCategory\HeurekaCategoryFacade;

class HeurekaFeedItemFactory extends BaseHeurekaFeedItemFactory
{
    /**
     * @var \App\Model\Product\Availability\ProductAvailabilityFacade
     */
    private ProductAvailabilityFacade $productAvailabilityFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPriceCalculationForCustomerUser $productPriceCalculationForCustomerUser
     * @param \Shopsys\ProductFeed\HeurekaBundle\Model\FeedItem\HeurekaProductDataBatchLoader $heurekaProductDataBatchLoader
     * @param \Shopsys\ProductFeed\HeurekaBundle\Model\HeurekaCategory\HeurekaCategoryFacade $heurekaCategoryFacade
     * @param \App\Model\Category\CategoryFacade $categoryFacade
     * @param \App\Model\Product\Availability\ProductAvailabilityFacade $productAvailabilityFacade
     */
    public function __construct(
        ProductPriceCalculationForCustomerUser $productPriceCalculationForCustomerUser,
        HeurekaProductDataBatchLoader $heurekaProductDataBatchLoader,
        HeurekaCategoryFacade $heurekaCategoryFacade,
        CategoryFacade $categoryFacade,
        ProductAvailabilityFacade $productAvailabilityFacade
    ) {
        parent::__construct(
            $productPriceCalculationForCustomerUser,
            $heurekaProductDataBatchLoader,
            $heurekaCategoryFacade,
            $categoryFacade
        );

        $this->productAvailabilityFacade = $productAvailabilityFacade;
    }

    /**
     * @param \App\Model\Product\Product $product
     * @param \Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig $domainConfig
     * @return \Shopsys\ProductFeed\HeurekaBundle\Model\FeedItem\HeurekaFeedItem
     */
    public function create(Product $product, DomainConfig $domainConfig): HeurekaFeedItem
    {
        $mainVariantId = $product->isVariant() ? $product->getMainVariant()->getId() : null;

        return new HeurekaFeedItem(
            $product->getId(),
            $mainVariantId,
            $product->getFullname($domainConfig->getLocale()),
            $product->getDescription($domainConfig->getId()),
            $this->productDataBatchLoader->getProductUrl($product, $domainConfig),
            $this->productDataBatchLoader->getProductImageUrl($product, $domainConfig),
            $this->getBrandName($product),
            $product->getEan(),
            $this->productAvailabilityFacade->getProductAvailabilityDaysByDomainId($product, $domainConfig->getId()),
            $this->getPrice($product, $domainConfig),
            $this->getHeurekaCategoryFullName($product, $domainConfig),
            $this->productDataBatchLoader->getProductParametersByName($product, $domainConfig),
            $this->productDataBatchLoader->getProductCpc($product, $domainConfig)
        );
    }
}
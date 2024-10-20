<?php

declare(strict_types=1);

namespace App\Model\Order\Processing\OrderProcessorMiddleware;

use Override;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItemData;
use Shopsys\FrameworkBundle\Model\Order\Item\QuantifiedItemPrice;
use Shopsys\FrameworkBundle\Model\Order\Item\QuantifiedProduct;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessorMiddleware\AddProductsMiddleware as BaseAddProductsMiddleware;

/**
 * @property \App\Model\Order\Item\OrderItemDataFactory $orderItemDataFactory
 * @method __construct(\Shopsys\FrameworkBundle\Model\Product\Pricing\QuantifiedProductPriceCalculation $quantifiedProductPriceCalculation, \App\Model\Order\Item\OrderItemDataFactory $orderItemDataFactory)
 */
class AddProductsMiddleware extends BaseAddProductsMiddleware
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Item\QuantifiedItemPrice $quantifiedItemPrice
     * @param \Shopsys\FrameworkBundle\Model\Order\Item\QuantifiedProduct $quantifiedProduct
     * @param string $locale
     * @return \App\Model\Order\Item\OrderItemData
     */
    #[Override]
    protected function createProductItemData(
        QuantifiedItemPrice $quantifiedItemPrice,
        QuantifiedProduct $quantifiedProduct,
        string $locale,
    ): OrderItemData {
        /** @var \App\Model\Order\Item\OrderItemData $orderItemData */
        $orderItemData = parent::createProductItemData($quantifiedItemPrice, $quantifiedProduct, $locale);

        /** @var \App\Model\Product\Product $product */
        $product = $quantifiedProduct->getProduct();

        $orderItemData->name = $product->getFullname($locale);

        return $orderItemData;
    }
}

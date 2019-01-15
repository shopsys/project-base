<?php

namespace Shopsys\ShopBundle\Model\Product;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\Plugin\Cron\SimpleCronModuleInterface;
use Shopsys\ShopBundle\Model\Pricing\Vat\VatFacade;
use Symfony\Bridge\Monolog\Logger;

class ImportProductsCronModule implements SimpleCronModuleInterface
{
    const PRODUCT_DATA_URL = 'https://private-anon-38d0154157-ssfwbasicdataimportdemo.apiary-mock.com/products';
    const LOCALE = 'en';
    const PRICING_GROUP_ID = 1; // maybe better PricingGroupSettingFacade::getDefaultPricingGroupByDomainId(); ?

    /**
     * @var \Shopsys\ShopBundle\Model\Product\ProductFacade
     */
    private $productFacade;

    /**
     * @var \Shopsys\ShopBundle\Model\Product\ProductDataFactory
     */
    private $productDataFactory;

    /**
     * @var \Shopsys\ShopBundle\Model\Pricing\Vat\VatFacade
     */
    private $vatFacade;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductFacade $productFacade
     * @param \Shopsys\ShopBundle\Model\Product\ProductDataFactory $productDataFactory
     * @param \Shopsys\ShopBundle\Model\Pricing\Vat\VatFacade $vatFacade
     */
    public function __construct(
        ProductFacade $productFacade,
        ProductDataFactory $productDataFactory,
        VatFacade $vatFacade
    ) {
        $this->productFacade = $productFacade;
        $this->productDataFactory = $productDataFactory;
        $this->vatFacade = $vatFacade;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function run()
    {
        $externalProductsJsonData = \file_get_contents(self::PRODUCT_DATA_URL);
        $externalProductsData = \json_decode($externalProductsJsonData, true);
        $this->importExternalProductsData($externalProductsData);
    }

    /**
     * @param array $externalProductsData
     */
    private function importExternalProductsData(array $externalProductsData)
    {
        foreach ($externalProductsData as $externalProductData) {
            $extId = $externalProductData['id'];

            $product = $this->productFacade->findByExternalId($extId);
            if ($product === null) {
                $this->createProduct($externalProductData);
                $this->logger->info(sprintf('Product with ext Id "%s" created', $extId));
            } else {
                $this->editProduct($product, $externalProductData);
                $this->logger->info(sprintf('Product with ext Id "%s" edited', $extId));
            }
        }
    }

    /**
     * @param array $externalProductData
     */
    private function createProduct(array $externalProductData)
    {
        $productData = $this->productDataFactory->create();
        $this->fillProductData($productData, $externalProductData);

        $this->productFacade->create($productData);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Product\Product $product
     * @param array $externalProductData
     */
    private function editProduct(Product $product, array $externalProductData)
    {
        $productData = $this->productDataFactory->createFromProduct($product);
        $this->fillProductData($productData, $externalProductData);

        $this->productFacade->edit($product->getId(), $productData);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductData $productData
     * @param array $externalProductData
     */
    private function fillProductData(ProductData $productData, array $externalProductData)
    {
        $productData->name[self::LOCALE] = $externalProductData['name']; // I must not use Localization::getLocale as it calls this->domain and it is not set in CLI :)
        $productData->manualInputPricesByPricingGroupId[self::PRICING_GROUP_ID] = $externalProductData['price_without_vat'];
        $vatByPercent = $this->vatFacade->getVatByPercent($externalProductData['vat_percent']);
        $productData->vat = $vatByPercent;
        $productData->ean = $externalProductData['ean'];
        $productData->descriptions[Domain::FIRST_DOMAIN_ID] = $externalProductData['description'];
        $productData->usingStock = true; // it is not obvious that I need to do this :(
        $productData->stockQuantity = $externalProductData['stock_quantity'];
        $productData->extId = $externalProductData['id'];
    }
}

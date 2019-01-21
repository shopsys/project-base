<?php

namespace Shopsys\ShopBundle\Model\Product;

use Shopsys\Plugin\Cron\SimpleCronModuleInterface;
use Symfony\Bridge\Monolog\Logger;

class ImportProductsCronModule implements SimpleCronModuleInterface
{
    const DAT_URL ='https://private-anon-38d0154157-ssfwbasicdataimportdemo.apiary-mock.com/products';

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    protected $logger;

    /**
     * @var \Shopsys\ShopBundle\Model\Product\ProductFacade
     */
    private $productFacade;

    /**
     * @var \Shopsys\ShopBundle\Model\Product\ProductDataFactory
     */
    private $productDataFactory;

    /**
     * @param \Shopsys\ShopBundle\Model\Product\ProductFacade $productFacade
     * @param \Shopsys\ShopBundle\Model\Product\ProductDataFactory $productDataFactory
     */
    public function __construct(ProductFacade $productFacade, ProductDataFactory $productDataFactory)
    {
        $this->productFacade = $productFacade;
        $this->productDataFactory = $productDataFactory;
    }

    /**
     * @param \Symfony\Bridge\Monolog\Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * This method is called to run the CRON module.
     */
    public function run()
    {
        $this->logger->info('Dowloading data...');
        $data = \file_get_contents(self::DAT_URL);
        $this->logger->info('Decoding data...');
        $decodedData = \json_decode($data, true);

    }

    private function processData(array $externalProductsData)
    {
        foreach ($externalProductsData as $externalProductData) {
            $extId = $externalProductData['id'];
            $product = $this->productFacade->findByExtId($extId);
            if ($product === null) {
                $this->createProduct($externalProductData);
            } else {
                //$this->editProducts();
            }
        }
    }

    private function createProduct(array $externalProductData)
    {
        $productData = $this->productDataFactory->create();
        $this->fillProductData($productData, $externalProductData);

        $this->productFacade->create($productData);
    }

    private function fillProductData(ProductData $productData, array $externalProductData)
    {
        $productData->extId = $externalProductData['id'];
        $productData->name['en'] = $externalProductData['name'];
        //$productData->manualInputPricesByPricingGroupId [1] = $externalProductData;

    }
}

<?php

declare(strict_types=1);

namespace App\Model\Product\Transfer\Akeneo;

use App\Component\Akeneo\Transfer\AbstractAkeneoImportTransfer;
use App\Component\Akeneo\Transfer\AkeneoImportTransferDependency;
use Generator;
use RuntimeException;

class AkeneoImportProductDetailFacade extends AbstractAkeneoImportTransfer
{
    /**
     * @var string[]
     */
    private array $productIdentifierList;

    /**
     * @param \App\Component\Akeneo\Transfer\AkeneoImportTransferDependency $akeneoImportTransferDependency
     * @param \App\Model\Product\Transfer\Akeneo\ProductTransferAkeneoFacade $productTransferAkeneoFacade
     * @param \App\Model\Product\Transfer\Akeneo\TransferredProductProcessor $transferredProductProcessor
     */
    public function __construct(
        AkeneoImportTransferDependency $akeneoImportTransferDependency,
        private ProductTransferAkeneoFacade $productTransferAkeneoFacade,
        private TransferredProductProcessor $transferredProductProcessor,
    ) {
        parent::__construct($akeneoImportTransferDependency);
    }

    /**
     * @param string[] $identifierList
     */
    public function downloadProductDetailsByIdentifierList(array $identifierList): void
    {
        $this->productIdentifierList = $identifierList;
        $this->runTransfer();
    }

    /**
     * @return \Generator
     */
    protected function getData(): Generator
    {
        foreach ($this->productIdentifierList as $productIdentifier) {
            try {
                yield $this->productTransferAkeneoFacade->getProductByIdentifier($productIdentifier);
            } catch (RuntimeException $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
    }

    protected function doBeforeTransfer(): void
    {
        $this->logger->info('Transfer product detail data from Akeneo ...');
    }

    /**
     * @param array $akeneoProductDetailData
     */
    protected function processItem($akeneoProductDetailData): void
    {
        $this->transferredProductProcessor->processProductDetail($akeneoProductDetailData, $this->logger);
    }

    protected function doAfterTransfer(): void
    {
        $this->logger->info('Transfer is done.');
    }

    /**
     * @return string
     */
    public function getTransferName(): string
    {
        return t('Products accessories transfer');
    }

    /**
     * @return string
     */
    public function getTransferIdentifier(): string
    {
        return 'akeneoProductAccessoriesTransfer';
    }
}

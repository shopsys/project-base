<?php

declare(strict_types=1);

namespace App\FrontendApi\Model\Image;

use App\Component\Image\ImageFacade;
use App\FrontendApi\Model\Image\ImageFacade as FrontendApiImageFacade;
use GraphQL\Executor\Promise\Promise;
use GraphQL\Executor\Promise\PromiseAdapter;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Image\Config\ImageConfig;
use Shopsys\FrameworkBundle\Component\Image\Config\ImageEntityConfig;
use Shopsys\FrameworkBundle\Component\Image\Config\ImageSizeConfig;
use Shopsys\FrameworkBundle\Component\Image\Exception\ImageNotFoundException;
use Shopsys\FrameworkBundle\Component\Image\Image;
use Shopsys\FrameworkBundle\Component\Utils\Utils;

class FirstImageBatchLoader
{
    /**
     * @param \GraphQL\Executor\Promise\PromiseAdapter $promiseAdapter
     * @param \App\FrontendApi\Model\Image\ImageFacade $frontendApiImageFacade
     * @param \App\Component\Image\ImageFacade $imageFacade
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     */
    public function __construct(
        private readonly PromiseAdapter $promiseAdapter,
        private readonly FrontendApiImageFacade $frontendApiImageFacade,
        private readonly ImageFacade $imageFacade,
        private readonly Domain $domain
    ) {
    }

    /**
     * @param \App\FrontendApi\Model\Image\ImageBatchLoadData[] $imagesBatchLoadData
     * @return \GraphQL\Executor\Promise\Promise
     */
    public function loadByBatchData(array $imagesBatchLoadData): Promise
    {
        $imagesBatchLoadDataByEntityNameAndType = $this->getImageBatchLoadDataArrayIndexedByEntityAndType($imagesBatchLoadData);

        $imagesIndexedByImageBatchLoadDataId = [];
        foreach ($imagesBatchLoadDataByEntityNameAndType as $entityName => $dataByTypes) {
            foreach ($dataByTypes as $type => $imagesBatchLoadData) {
                $imagesIndexedByImageBatchLoadDataId = array_merge($imagesIndexedByImageBatchLoadDataId, $this->getImagesByEntityNameAndTypeIndexedByDataId($imagesBatchLoadData, $entityName, $type));
            }
        }

        return $this->promiseAdapter->all($this->sortImagesByOriginalInputData($imagesIndexedByImageBatchLoadDataId, $imagesBatchLoadData));
    }

    /**
     * @param \App\FrontendApi\Model\Image\ImageBatchLoadData[] $imagesBatchLoadData
     * @param string $entityName
     * @param string $type
     * @return array<string, array|null>
     */
    private function getImagesByEntityNameAndTypeIndexedByDataId(array $imagesBatchLoadData, string $entityName, string $type): array
    {
        if ($type === ImageEntityConfig::WITHOUT_NAME_KEY) {
            $type = null;
        }
        $entityIds = array_map(fn (ImageBatchLoadData $imageBatchLoadData) => $imageBatchLoadData->getEntityId(), $imagesBatchLoadData);
        $imagesIndexedByEntityId = $this->frontendApiImageFacade->getImagesByTypeAndPositionIndexedByEntityId($entityIds, $entityName, $type);

        $images = [];
        foreach ($imagesBatchLoadData as $imageBatchLoadData) {
            if (!isset($imagesIndexedByEntityId[$imageBatchLoadData->getEntityId()]) || $imagesIndexedByEntityId[$imageBatchLoadData->getEntityId()] === null) { // @phpstan-ignore-line
                continue;
            }

            $entityResolvedImage = $this->getResolvedImage($imagesIndexedByEntityId[$imageBatchLoadData->getEntityId()], $imageBatchLoadData->getSizeConfigs());
            $images[$imageBatchLoadData->getId()] = $entityResolvedImage;
        }

        return $images;
    }

    /**
     * @param \App\FrontendApi\Model\Image\ImageBatchLoadData[] $imagesBatchLoadData
     * @return \App\FrontendApi\Model\Image\ImageBatchLoadData[][][]
     */
    private function getImageBatchLoadDataArrayIndexedByEntityAndType(array $imagesBatchLoadData): array
    {
        $result = [];
        foreach ($imagesBatchLoadData as $imageBatchLoadData) {
            $entityName = $imageBatchLoadData->getEntityName();
            $type = Utils::ifNull($imageBatchLoadData->getType(), ImageEntityConfig::WITHOUT_NAME_KEY);
            $result[$entityName][$type][] = $imageBatchLoadData;
        }

        return $result;
    }

    /**
     * @param array<string, array|null> $imagesIndexedByImageBatchLoadDataId
     * @param \App\FrontendApi\Model\Image\ImageBatchLoadData[] $imagesBatchLoadData
     * @return array<int, array|null>
     */
    private function sortImagesByOriginalInputData(array $imagesIndexedByImageBatchLoadDataId, array $imagesBatchLoadData): array
    {
        $sortedImages = [];
        foreach ($imagesBatchLoadData as $imageBatchLoadData) {
            if (array_key_exists($imageBatchLoadData->getId(), $imagesIndexedByImageBatchLoadDataId) === false) {
                $sortedImages[] = null;

                continue;
            }

            $sortedImages[] = $imagesIndexedByImageBatchLoadDataId[$imageBatchLoadData->getId()];
        }

        return array_values($sortedImages);
    }

    /**
     * @param \App\Component\Image\Image $image
     * @param \Shopsys\FrameworkBundle\Component\Image\Config\ImageSizeConfig[] $sizeConfigs
     * @return array|null
     */
    private function getResolvedImage(Image $image, array $sizeConfigs): ?array
    {
        $imageSizes = [];
        foreach ($sizeConfigs as $sizeConfig) {
            try {
                $imageSizes[] = $this->getResolvedImageSize($image, $sizeConfig);
            } catch (ImageNotFoundException $exception) {
                continue;
            }
        }

        if ($imageSizes === []) {
            return null;
        }

        return [
            'position' => $image->getPosition(),
            'type' => $image->getType(),
            'sizes' => $imageSizes,
        ];
    }

    /**
     * @param \App\Component\Image\Image $image
     * @param \Shopsys\FrameworkBundle\Component\Image\Config\ImageSizeConfig $sizeConfig
     * @return array
     */
    private function getResolvedImageSize(Image $image, ImageSizeConfig $sizeConfig): array
    {
        return [
            'width' => $sizeConfig->getWidth(),
            'height' => $sizeConfig->getHeight(),
            'size' => $sizeConfig->getName() ?? ImageConfig::DEFAULT_SIZE_NAME,
            'url' => $this->imageFacade->getImageUrl(
                $this->domain->getCurrentDomainConfig(),
                $image,
                $sizeConfig->getName(),
                $image->getType()
            ),
            'additionalSizes' => $this->imageFacade->getAdditionalImagesData(
                $this->domain->getCurrentDomainConfig(),
                $image,
                $sizeConfig->getName(),
                $image->getType()
            ),
        ];
    }
}
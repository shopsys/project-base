<?php

declare(strict_types=1);

namespace App\Component\Image;

use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\MountManager;
use Psr\Log\LoggerInterface;
use Shopsys\FrameworkBundle\Component\Cdn\CdnFacade;
use Shopsys\FrameworkBundle\Component\FileUpload\FileUpload;
use Shopsys\FrameworkBundle\Component\Image\Config\ImageConfig;
use Shopsys\FrameworkBundle\Component\Image\ImageFacade as BaseImageFacade;
use Shopsys\FrameworkBundle\Component\Image\ImageFactoryInterface;
use Shopsys\FrameworkBundle\Component\Image\ImageLocator;
use Shopsys\FrameworkBundle\Component\Image\ImageRepository;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @property \App\Component\Image\ImageRepository $imageRepository
 * @method \Shopsys\FrameworkBundle\Component\Image\Image[] getAllImagesByEntity(object $entity)
 * @method deleteImageFiles(\Shopsys\FrameworkBundle\Component\Image\Image $image)
 * @method \Shopsys\FrameworkBundle\Component\Image\Image getImageByObject(object $imageOrEntity, string|null $type = null)
 * @method \Shopsys\FrameworkBundle\Component\Image\Image getById(int $imageId)
 * @method \Shopsys\FrameworkBundle\Component\Image\Image getImageByEntity(object $entity, string|null $type)
 * @method \Shopsys\FrameworkBundle\Component\Image\Image[] getImagesByEntityIndexedById(object $entity, string|null $type)
 * @method string|null getSeoNameByImageAndLocale(\Shopsys\FrameworkBundle\Component\Image\Image $image, string $locale)
 */
class ImageFacade extends BaseImageFacade
{
    /**
     * @param string $imageUrlPrefix
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Shopsys\FrameworkBundle\Component\Image\Config\ImageConfig $imageConfig
     * @param \App\Component\Image\ImageRepository $imageRepository
     * @param \League\Flysystem\FilesystemOperator $filesystem
     * @param \Shopsys\FrameworkBundle\Component\FileUpload\FileUpload $fileUpload
     * @param \Shopsys\FrameworkBundle\Component\Image\ImageLocator $imageLocator
     * @param \Shopsys\FrameworkBundle\Component\Image\ImageFactory $imageFactory
     * @param \League\Flysystem\MountManager $mountManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Shopsys\FrameworkBundle\Component\Cdn\CdnFacade $cdnFacade
     * @param \Symfony\Contracts\Cache\CacheInterface|\Symfony\Component\Cache\Adapter\AdapterInterface $cache
     */
    public function __construct(
        string $imageUrlPrefix,
        EntityManagerInterface $em,
        ImageConfig $imageConfig,
        ImageRepository $imageRepository,
        FilesystemOperator $filesystem,
        FileUpload $fileUpload,
        ImageLocator $imageLocator,
        ImageFactoryInterface $imageFactory,
        MountManager $mountManager,
        LoggerInterface $logger,
        CdnFacade $cdnFacade,
        CacheInterface|AdapterInterface $cache,
    ) {
        parent::__construct(
            $imageUrlPrefix,
            $em,
            $imageConfig,
            $imageRepository,
            $filesystem,
            $fileUpload,
            $imageLocator,
            $imageFactory,
            $mountManager,
            $logger,
            $cdnFacade,
            $cache,
        );
    }

    /**
     * @param mixed $entity
     * @param array $images
     */
    public function deleteImages($entity, array $images): void
    {
        $entityName = $this->imageConfig->getEntityName($entity);
        $entityId = $this->getEntityId($entity);

        // files will be deleted in doctrine listener
        foreach ($images as $image) {
            $image->checkForDelete($entityName, $entityId);
        }

        foreach ($images as $image) {
            $imageToRemove = $this->imageRepository->findById($image->getId());

            if ($imageToRemove !== null) {
                $this->em->remove($imageToRemove);
            }
        }
        $this->em->flush();
    }

    /**
     * @param \Shopsys\FrameworkBundle\Component\Image\Image[] $orderedImages
     */
    protected function saveImageOrdering($orderedImages): void
    {
        // Image entity can be cached, and It caused no persisted entity -> fatal on flush
        $persistedImages = [];

        foreach ($orderedImages as $image) {
            if ($this->em->getUnitOfWork()->isInIdentityMap($image) === true) {
                $persistedImages[] = $image;
            } else {
                $persistedImages[] = $this->getById($image->getId());
            }
        }

        parent::saveImageOrdering($persistedImages);
    }
}

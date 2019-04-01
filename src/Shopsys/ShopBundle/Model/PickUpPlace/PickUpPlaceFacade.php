<?php

namespace Shopsys\ShopBundle\Model\PickUpPlace;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\ShopBundle\Model\Transport\Transport;
use Symfony\Bridge\Monolog\Logger;

class PickUpPlaceFacade
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceLoader
     */
    protected $pickUpPlaceLoader;

    /**
     * @var \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceRepository
     */
    protected $pickUpPlaceRepository;

    /**
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceRepository $pickUpPlaceRepository
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceLoader $pickUpPlaceLoader
     */
    public function __construct(
        PickUpPlaceRepository $pickUpPlaceRepository,
        EntityManagerInterface $em,
        PickUpPlaceLoader $pickUpPlaceLoader
    ) {
        $this->pickUpPlaceRepository = $pickUpPlaceRepository;
        $this->em = $em;
        $this->pickUpPlaceLoader = $pickUpPlaceLoader;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceData $pickUpPlaceData
     * @return \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace
     */
    public function create(PickUpPlaceData $pickUpPlaceData)
    {
        $pickUpPlace = new PickUpPlace($pickUpPlaceData);
        $this->em->persist($pickUpPlace);
        $this->em->flush($pickUpPlace);
        return $pickUpPlace;
    }

    /**
     * @param string $feedUrl
     * @param \Symfony\Bridge\Monolog\Logger $logger
     * @param string $transportType
     */
    public function downloadPickUpPlaceFromFeed($feedUrl, Logger $logger, $transportType)
    {
        $this->pickUpPlaceRepository->markAllAsPendingByTransportType($transportType);
        $pickUpPlacesData = $this->pickUpPlaceLoader->load($feedUrl, $transportType);
        $this->refreshPickUpPlace($pickUpPlacesData, $logger);
        $this->pickUpPlaceRepository->deactivateAllPendingByTransportType(Transport::TYPE_ZASILKOVNA);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceData[] $pickUpPlacesData
     * @param \Symfony\Bridge\Monolog\Logger $logger
     */
    protected function refreshPickUpPlace(array $pickUpPlacesData, Logger $logger)
    {
        $newPickUpPlacesCount = 0;
        $updatedPickUpPlacesCount = 0;
        foreach ($pickUpPlacesData as $pickUpPlaceData) {
            $pickUpPlace = $this->pickUpPlaceRepository->findByPlaceIdAndTransportType(
                $pickUpPlaceData->placeId,
                $pickUpPlaceData->transportType
            );
            if ($pickUpPlace === null) {
                $this->create($pickUpPlaceData);
                $newPickUpPlacesCount++;
            } else {
                $pickUpPlace->edit($pickUpPlaceData);
                $pickUpPlace->markAsNotPending();
                $this->em->flush($pickUpPlace);
                $updatedPickUpPlacesCount++;
            }
        }
        $message = sprintf(
            'PickUpPlaces were updated. Number of new places: %d, updated places: %d',
            $newPickUpPlacesCount,
            $updatedPickUpPlacesCount
        );
        $logger->info($message);
    }
}

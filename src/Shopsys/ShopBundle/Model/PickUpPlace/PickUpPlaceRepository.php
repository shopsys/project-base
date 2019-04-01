<?php

namespace Shopsys\ShopBundle\Model\PickUpPlace;

use Doctrine\ORM\EntityManagerInterface;

class PickUpPlaceRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getPickUpPlaceRepository()
    {
        return $this->em->getRepository(PickUpPlace::class);
    }

    /**
     * @param string $transportType
     * @return \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace[]
     */
    public function findAllByTransportType($transportType)
    {
        return $this->getPickUpPlaceRepository()->findBy([
            'transportType' => $transportType,
        ]);
    }

    /**
     * @param string $transportType
     */
    public function markAllAsPendingByTransportType($transportType)
    {
        $pickUpPlaces = $this->findAllByTransportType($transportType);
        foreach ($pickUpPlaces as $pickUpPlace) {
            $pickUpPlace->markAsPending();
        }
        $this->em->flush($pickUpPlaces);
    }

    /**
     * @param string $transportType
     */
    public function deactivateAllPendingByTransportType($transportType)
    {
        $pickUpPlaces = $this->findAllPendingByTransport($transportType);
        foreach ($pickUpPlaces as $pickUpPlace) {
            $pickUpPlace->markAsInactive();
        }
        $this->em->flush($pickUpPlaces);
    }

    /**
     * @param $transportType
     * @return \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace[]
     */
    public function findAllPendingByTransport($transportType)
    {
        return $this->getPickUpPlaceRepository()->findBy([
            'transportType' => $transportType,
            'pending' => true,
        ]);
    }

    /**
     * @param int $placeId
     * @param string $transportType
     * @return null|\Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace
     */
    public function findByPlaceIdAndTransportType($placeId, $transportType)
    {
        return $this->getPickUpPlaceRepository()->findOneBy([
            'placeId' => $placeId,
            'transportType' => $transportType,
        ]);
    }
}

<?php

namespace Shopsys\ShopBundle\Model\PickUpPlace;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Component\String\DatabaseSearching;
use Shopsys\ShopBundle\Model\PickUpPlace\Exception\PickUpPlaceNotFoundException;

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

    /**
     * @param int $id
     * @return null|\Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace
     */
    public function getById($id)
    {
        $pickUpPlace = $this->getPickUpPlaceRepository()->find($id);
        if ($pickUpPlace === null) {
            $message = sprintf('PickUpPlace with id `%d` was not found.', $id);
            throw new PickUpPlaceNotFoundException($message);
        }
        return $pickUpPlace;
    }

    /**
     * @param string|null $searchQuery
     * @param mixed $countryCodes
     * @param $transportType
     * @return \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlace[]
     */
    public function findActiveBySearchQueryAndTransportType($searchQuery, $countryCodes, $transportType)
    {
        if ($searchQuery === null) {
            return [];
        }
        $pickUpPlaceQueryBuilder = $this->getPickUpPlaceRepository()->createQueryBuilder('pup');
        if ($searchQuery !== null && $searchQuery !== '') {
            $normalizedPostCode = str_replace(' ', '', $searchQuery);
            $pickUpPlaceQueryBuilder->andWhere('NORMALIZE(pup.city) LIKE NORMALIZE(:city)'
                . ' OR NORMALIZE(pup.postCode) LIKE NORMALIZE(:postCode)'
                . ' OR NORMALIZE(pup.street) LIKE NORMALIZE(:street)')
                ->setParameter('city', DatabaseSearching::getLikeSearchString($searchQuery) . '%')
                ->setParameter('postCode', DatabaseSearching::getLikeSearchString($normalizedPostCode) . '%')
                ->setParameter('street', DatabaseSearching::getLikeSearchString($searchQuery) . '%');
        }
        $pickUpPlaceQueryBuilder->andWhere('pup.transportType = :transportType')
            ->setParameter('transportType', $transportType);
        $pickUpPlaceQueryBuilder->andWhere('pup.countryCode IN (:countryCode)')
            ->setParameter('countryCode', $countryCodes);
        $pickUpPlaceQueryBuilder->andWhere('pup.active = TRUE');
        return $pickUpPlaceQueryBuilder->orderBy(
            'NORMALIZE(pup.name), NORMALIZE(pup.city), NORMALIZE(pup.street), pup.postCode'
        )->getQuery()->execute();
    }
}

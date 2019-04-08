<?php

namespace Shopsys\ShopBundle\Model\PickUpPlace;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="pick_up_place",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="place_unique", columns={"transport_type", "place_id"})}
 *     )
 * @ORM\Entity
 */
class PickUpPlace
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $transportType;

    /**
     * ID from pickup system
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $placeId;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    protected $countryCode;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     */
    protected $city;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     */
    protected $street;

    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     */
    protected $postCode;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    protected $gpsLatitude;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    protected $gpsLongitude;

    /**
     * @var string
     * @ORM\Column(type="string", length=250)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $pending;

    /**
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceData $pickUpPlaceData
     */
    public function __construct(PickUpPlaceData $pickUpPlaceData)
    {
        $this->placeId = $pickUpPlaceData->placeId;
        $this->transportType = $pickUpPlaceData->transportType;
        $this->countryCode = $pickUpPlaceData->countryCode;
        $this->city = $pickUpPlaceData->city;
        $this->street = $pickUpPlaceData->street;
        $this->postCode = $pickUpPlaceData->postCode;
        $this->gpsLatitude = $pickUpPlaceData->gpsLatitude;
        $this->gpsLongitude = $pickUpPlaceData->gpsLongitude;
        $this->name = $pickUpPlaceData->name;
        $this->description = $pickUpPlaceData->description;
        $this->active = true;
        $this->pending = false;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceData $pickUpPlaceData
     */
    public function edit(PickUpPlaceData $pickUpPlaceData)
    {
        $this->placeId = $pickUpPlaceData->placeId;
        $this->transportType = $pickUpPlaceData->transportType;
        $this->countryCode = $pickUpPlaceData->countryCode;
        $this->city = $pickUpPlaceData->city;
        $this->street = $pickUpPlaceData->street;
        $this->postCode = $pickUpPlaceData->postCode;
        $this->gpsLatitude = $pickUpPlaceData->gpsLatitude;
        $this->gpsLongitude = $pickUpPlaceData->gpsLongitude;
        $this->name = $pickUpPlaceData->name;
        $this->description = $pickUpPlaceData->description;
    }

    public function markAsPending()
    {
        $this->pending = true;
    }

    public function markAsNotPending()
    {
        $this->pending = false;
    }

    public function markAsInactive()
    {
        $this->active = false;
        $this->pending = false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTransportType()
    {
        return $this->transportType;
    }

    /**
     * @return int
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @return float
     */
    public function getGpsLatitude()
    {
        return $this->gpsLatitude;
    }

    /**
     * @return float
     */
    public function getGpsLongitude()
    {
        return $this->gpsLongitude;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->pending;
    }

    /**
     * @return string
     */
    public function getFullAddress()
    {
        return sprintf(
            '%s, %s, %s',
            $this->getStreet(),
            $this->getCity(),
            $this->getPostCode()
        );
    }
}

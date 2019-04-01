<?php

namespace Shopsys\ShopBundle\Model\PickUpPlace;

class PickUpPlaceData
{
    /**
     * @var int|null
     */
    public $placeId;

    /**
     * @var string
     */
    public $transportType;

    /**
     * @var string
     */
    public $countryCode;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $street;

    /**
     * @var string
     */
    public $postCode;

    /**
     * @var float|null
     */
    public $gpsLatitude;

    /**
     * @var float|null
     */
    public $gpsLongitude;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    public function __construct()
    {
        $this->placeId = null;
        $this->transportType = '';
        $this->countryCode = '';
        $this->city = '';
        $this->street = '';
        $this->postCode = '';
        $this->gpsLatitude = null;
        $this->gpsLongitude = null;
        $this->name = '';
        $this->description = '';
    }
}

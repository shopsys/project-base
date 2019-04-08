<?php

namespace Shopsys\ShopBundle\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\FrontOrderData as BaseFrontOrderData;
use Shopsys\FrameworkBundle\Model\Order\OrderDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Order\OrderDataMapper as BaseOrderDataMapper;
use Shopsys\ShopBundle\Model\Country\CountryFacade;

class OrderDataMapper extends BaseOrderDataMapper
{
    /**
     * @var \Shopsys\ShopBundle\Model\Country\CountryFacade
     */
    private $countryFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderDataFactoryInterface $orderDataFactory
     * @param \Shopsys\ShopBundle\Model\Country\CountryFacade $countryFacade
     */
    public function __construct(OrderDataFactoryInterface $orderDataFactory, CountryFacade $countryFacade)
    {
        parent::__construct($orderDataFactory);
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Order\FrontOrderData $frontOrderData
     * @return \Shopsys\ShopBundle\Model\Order\OrderData
     */
    public function getOrderDataFromFrontOrderData(BaseFrontOrderData $frontOrderData)
    {
        /** @var \Shopsys\ShopBundle\Model\Order\OrderData $orderData */
        $orderData = parent::getOrderDataFromFrontOrderData($frontOrderData);

        $orderData->pickUpPlace = $orderData->transport !== null && $orderData->transport->isPickUpPlaceType() ? $frontOrderData->pickUpPlace : null;

        if ($orderData->pickUpPlace !== null) {
            $this->fillPickUpPlaceDeliveryData($orderData, $frontOrderData);
        }

        return $orderData;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Order\OrderData $orderData
     * @param \Shopsys\ShopBundle\Model\Order\FrontOrderData $frontOrderData
     */
    protected function fillPickUpPlaceDeliveryData(OrderData $orderData, FrontOrderData $frontOrderData)
    {
        $orderData->deliveryFirstName = $frontOrderData->firstName;
        $orderData->deliveryLastName = $frontOrderData->lastName;
        $frontOrderData->deliveryAddressSameAsBillingAddress = false;
        $orderData->deliveryAddressSameAsBillingAddress = $frontOrderData->deliveryAddressSameAsBillingAddress;
        $frontOrderData->deliveryCompanyName = $orderData->pickUpPlace->getName();
        $orderData->deliveryCompanyName = $frontOrderData->deliveryCompanyName;
        $orderData->deliveryTelephone = null;
        $frontOrderData->deliveryStreet = $orderData->pickUpPlace->getStreet();
        $orderData->deliveryStreet = $frontOrderData->deliveryStreet;
        $frontOrderData->deliveryCity = $orderData->pickUpPlace->getCity();
        $orderData->deliveryCity = $frontOrderData->deliveryCity;
        $frontOrderData->deliveryPostcode = $orderData->pickUpPlace->getPostCode();
        $orderData->deliveryPostcode = $frontOrderData->deliveryPostcode;
        $frontOrderData->deliveryCountry = $this->countryFacade->getByCode($orderData->pickUpPlace->getCountryCode());
        $orderData->deliveryCountry = $frontOrderData->deliveryCountry;
    }
}

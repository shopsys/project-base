<?php

namespace Shopsys\ShopBundle\Controller\Front;

use Shopsys\FrameworkBundle\Component\String\TransformString;
use Shopsys\FrameworkBundle\Model\Country\CountryFacade;
use Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceFacade;
use Symfony\Component\HttpFoundation\Request;

class PickUpPlaceController extends FrontBaseController
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Country\CountryFacade
     */
    protected $countryFacade;

    /**
     * @var \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceFacade
     */
    protected $pickUpPlaceFacade;

    /**
     * @param \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceFacade $pickUpPlaceFacade
     * @param \Shopsys\FrameworkBundle\Model\Country\CountryFacade $countryFacade
     */
    public function __construct(PickUpPlaceFacade $pickUpPlaceFacade, CountryFacade $countryFacade)
    {
        $this->countryFacade = $countryFacade;
        $this->pickUpPlaceFacade = $pickUpPlaceFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $pickUpPlaceId = (int)$request->get('pickUpPlaceId');

        $pickUpPlaces = [];
        if ($pickUpPlaceId > 0) {
            $pickUpPlace = $this->pickUpPlaceFacade->getById($pickUpPlaceId);
            $pickUpPlaces[] = $pickUpPlace;
        }

        return $this->render('@ShopsysShop/Front/Inline/PickUpPlace/pickUpPlaceSearch.html.twig', [
            'pickUpPlaces' => $pickUpPlaces,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function autocompleteAction(Request $request)
    {
        $rawSearchQuery = $request->request->get('searchQuery', '');
        $transportType = $request->request->get('transportType');

        $searchQuery = TransformString::emptyToNull(trim($rawSearchQuery));

        /** @var $countryCodes string[] */
        $countryCodes = array_map(function ($country) {
            /** @var $country \Shopsys\FrameworkBundle\Model\Country\Country */
            return strtolower($country->getCode());
        }, $this->countryFacade->getAllEnabledOnCurrentDomain());

        $pickUpPlaces = $this->pickUpPlaceFacade->findActiveBySearchQueryAndTransportType(
            $searchQuery,
            $countryCodes,
            $transportType
        );

        return $this->render('@ShopsysShop/Front/Inline/PickUpPlace/autocompleteResult.html.twig', [
            'pickUpPlaces' => $pickUpPlaces,
        ]);
    }
}

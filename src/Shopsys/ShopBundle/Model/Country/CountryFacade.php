<?php

namespace Shopsys\ShopBundle\Model\Country;

use Shopsys\FrameworkBundle\Model\Country\CountryFacade as BaseCountryFacade;

class CountryFacade extends BaseCountryFacade
{
    /**
     * @param string $code
     * @return \Shopsys\FrameworkBundle\Model\Country\Country
     */
    public function getByCode($code)
    {
        return $this->countryRepository->getByCode($code);
    }
}

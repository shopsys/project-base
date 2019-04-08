<?php

namespace Shopsys\ShopBundle\Model\Country;

use Shopsys\FrameworkBundle\Model\Country\CountryRepository as BaseCountryRepository;

class CountryRepository extends BaseCountryRepository
{
    /**
     * @param string $code
     * @return \Shopsys\FrameworkBundle\Model\Country\Country
     */
    public function getByCode($code)
    {
        $country = $this->getCountryRepository()->findOneBy(['code' => strtoupper($code)]);

        if ($country === null) {
            $message = sprintf('Country with code ISO `%s` was not found.', $code);
            throw new \Shopsys\FrameworkBundle\Model\Country\Exception\CountryNotFoundException($message);
        }

        return $country;
    }
}

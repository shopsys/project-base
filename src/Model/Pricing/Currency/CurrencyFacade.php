<?php

declare(strict_types=1);

namespace App\Model\Pricing\Currency;

use Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade as BaseCurrencyFacade;

class CurrencyFacade extends BaseCurrencyFacade
{
    /**
     * @return string[]
     */
    public function getAllCodes(): array
    {
        $currencies = $this->getAll();

        return array_map(fn (Currency $currency): string => $currency->getCode(), $currencies);
    }
}

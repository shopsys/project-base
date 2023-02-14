<?php

declare(strict_types=1);

namespace App\Component\CnbExchangeRate;

use App\Model\Pricing\Currency\CurrencyFacade;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyDataFactory;

class CnbExchangeFacade
{
    private const EXCHANGE_RATE_INDEX = 4;

    private const CURRENCY_CODE = 3;

    /**
     * @var \App\Component\CnbExchangeRate\CnbExchangeRateClient
     */
    private CnbExchangeRateClient $cnbExchangeRateClient;

    /**
     * @var \App\Model\Pricing\Currency\CurrencyFacade
     */
    private CurrencyFacade $currencyFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyDataFactory
     */
    private CurrencyDataFactory $currencyDataFactory;

    /**
     * @param \App\Component\CnbExchangeRate\CnbExchangeRateClient $cnbExchangeRateClient
     * @param \App\Model\Pricing\Currency\CurrencyFacade $currencyFacade
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyDataFactory $currencyDataFactory
     */
    public function __construct(
        CnbExchangeRateClient $cnbExchangeRateClient,
        CurrencyFacade $currencyFacade,
        CurrencyDataFactory $currencyDataFactory
    ) {
        $this->cnbExchangeRateClient = $cnbExchangeRateClient;
        $this->currencyFacade = $currencyFacade;
        $this->currencyDataFactory = $currencyDataFactory;
    }

    public function downloadExchangeRates(): void
    {
        $cnbFile = $this->cnbExchangeRateClient->getCnbFileRates();

        $i = 0;
        while (($row = fgetcsv($cnbFile, null, '|')) !== false) {
            $i++;

            if ($this->isHeaderRow($i)) {
                continue;
            }

            $currencyCode = $row[self::CURRENCY_CODE];
            $currencyRateText = $row[self::EXCHANGE_RATE_INDEX];
            $currencyRate = str_replace(',', '.', $currencyRateText);

            if (!in_array($currencyCode, $this->currencyFacade->getAllCodes(), true)) {
                continue;
            }

            $currency = $this->currencyFacade->getByCode($currencyCode);
            $currencyData = $this->currencyDataFactory->createFromCurrency($currency);
            $currencyData->exchangeRate = $currencyRate;

            $this->currencyFacade->edit($currency->getId(), $currencyData);
        }
    }

    /**
     * @param int $i
     * @return bool
     */
    private function isHeaderRow(int $i): bool
    {
        return $i < 2;
    }
}

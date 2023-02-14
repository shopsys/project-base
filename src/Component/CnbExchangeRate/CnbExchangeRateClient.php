<?php

declare(strict_types=1);

namespace App\Component\CnbExchangeRate;

use App\Component\CnbExchangeRate\Exception\EmptyCnbFileException;
use App\Component\CnbExchangeRate\Exception\MissingConnectionInformationException;

class CnbExchangeRateClient
{
    /**
     * @var string
     */
    private string $apiUrl;

    /**
     * @param string $apiUrl
     */
    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        if ($this->apiUrl === '') {
            throw new MissingConnectionInformationException('CNB_RATES_URL');
        }

        return $this->apiUrl;
    }

    /**
     * @return resource
     */
    public function getCnbFileRates()
    {
        $cnbFile = fopen($this->getApiUrl(), 'rb');

        if ($cnbFile === false) {
            throw new EmptyCnbFileException();
        }

        return $cnbFile;
    }
}

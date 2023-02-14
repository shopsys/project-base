<?php

declare(strict_types=1);

namespace App\Component\CnbExchangeRate;

use Shopsys\Plugin\Cron\SimpleCronModuleInterface;
use Symfony\Bridge\Monolog\Logger;

class CnbExchangeRateCronModule implements SimpleCronModuleInterface
{
    /**
     * @var \App\Component\CnbExchangeRate\CnbExchangeFacade
     */
    private CnbExchangeFacade $cnbExchangeFacade;

    /**
     * @param \App\Component\CnbExchangeRate\CnbExchangeFacade $cnbExchangeFacade
     */
    public function __construct(CnbExchangeFacade $cnbExchangeFacade)
    {
        $this->cnbExchangeFacade = $cnbExchangeFacade;
    }

    /**
     * @param \Symfony\Bridge\Monolog\Logger $logger
     */
    public function setLogger(Logger $logger): void
    {
    }

    public function run(): void
    {
        $this->cnbExchangeFacade->downloadExchangeRates();
    }
}

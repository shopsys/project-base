<?php

declare(strict_types=1);

namespace App\Component\CnbExchangeRate\Exception;

use Exception;

class MissingConnectionInformationException extends Exception implements CnbExceptionInterface
{
    /**
     * @param string $property
     */
    public function __construct(string $property)
    {
        parent::__construct(sprintf('Required property "%s" has not set value in .env', $property), 2);
    }
}

<?php

declare(strict_types=1);

namespace App\Component\CnbExchangeRate\Exception;

use Exception;

class EmptyCnbFileException extends Exception implements CnbExceptionInterface
{
}

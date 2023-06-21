<?php

declare(strict_types=1);

namespace App\DataFixtures\Performance\Exception;

use Exception;

class UndefinedArrayKeyException extends Exception implements PerformanceException
{
    /**
     * @param string|int $key
     * @param \Exception|null $previous
     */
    public function __construct($key, ?Exception $previous = null)
    {
        parent::__construct('Key "' . $key . '" does not exists.', 0, $previous);
    }
}

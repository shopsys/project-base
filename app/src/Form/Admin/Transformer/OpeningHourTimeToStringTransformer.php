<?php

declare(strict_types=1);

namespace App\Form\Admin\Transformer;

use DateTime;
use Symfony\Component\Form\DataTransformerInterface;

class OpeningHourTimeToStringTransformer implements DataTransformerInterface
{
    /**
     * @param string|null $time
     * @return \DateTime|null
     */
    public function transform($time): ?DateTime
    {
        if ($time === null) {
            return null;
        }

        return new DateTime(sprintf('1970-01-01 %s:00', $time));
    }

    /**
     * @param \DateTime|null $dateTime
     * @return string|null
     */
    public function reverseTransform($dateTime): ?string
    {
        if ($dateTime === null) {
            return null;
        }

        return $dateTime->format('H:i');
    }
}

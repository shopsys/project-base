<?php

declare(strict_types=1);

namespace App\FrontendApi\Model\Product\Filter;

use Shopsys\FrameworkBundle\Model\Product\Parameter\ParameterValue;
use Shopsys\FrontendApiBundle\Model\Product\Filter\ParameterValueFilterOption as BaseParameterValueFilterOption;

class ParameterValueFilterOption extends BaseParameterValueFilterOption
{
    /**
     * @var \App\Model\Product\Parameter\ParameterValue
     */
    public ParameterValue $value;

    /**
     * @param \App\Model\Product\Parameter\ParameterValue $parameterValue
     * @param int $count
     * @param bool $isAbsolute
     * @param bool $isSelected
     */
    public function __construct(ParameterValue $parameterValue, int $count, bool $isAbsolute, public bool $isSelected)
    {
        parent::__construct($parameterValue, $count, $isAbsolute);
    }

    /**
     * @return string|null
     */
    public function getRgbHex(): ?string
    {
        return $this->value->getRgbHex();
    }
}

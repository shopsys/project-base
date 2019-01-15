<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Pricing\Vat;

use Shopsys\FrameworkBundle\Model\Pricing\Vat\Exception\VatNotFoundException;
use Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat;
use Shopsys\FrameworkBundle\Model\Pricing\Vat\VatRepository as BaseVatRepository;

class VatRepository extends BaseVatRepository
{
    /**
     * @param string $vatPercent
     * @return \Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat
     */
    public function getVatByPercent(string $vatPercent): Vat
    {
        $vat = $this->getVatRepository()->findOneBy(['percent' => $vatPercent]);
        /** @var $vat \Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat */
        if ($vat === null) {
            throw new VatNotFoundException(\sprintf('Vat not found by percent "%s"', $vatPercent));
        }

        return $vat;
    }
}

<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Pricing\Vat;

use Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat;
use Shopsys\FrameworkBundle\Model\Pricing\Vat\VatFacade as BaseVatFacade;

class VatFacade extends BaseVatFacade
{
    /**
     * @var \Shopsys\ShopBundle\Model\Pricing\Vat\VatRepository
     */
    protected $vatRepository;

    /**
     * @param string $vatPercent
     * @return \Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat
     */
    public function getVatByPercent(string $vatPercent): Vat
    {
        return $this->vatRepository->getVatByPercent($vatPercent);
    }
}

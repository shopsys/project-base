<?php

namespace Shopsys\ShopBundle\Model\Transport;

use Shopsys\FrameworkBundle\Model\Transport\Transport as BaseTransport;
use Shopsys\FrameworkBundle\Model\Transport\TransportData as BaseTransportData;
use Shopsys\FrameworkBundle\Model\Transport\TransportDataFactory as BaseTransportDataFactory;

class TransportDataFactory extends BaseTransportDataFactory
{
    /**
     * @return \Shopsys\ShopBundle\Model\Transport\TransportData
     */
    public function create(): BaseTransportData
    {
        $transportData = new TransportData();
        $this->fillNew($transportData);

        return $transportData;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Transport\Transport $transport
     * @return \Shopsys\ShopBundle\Model\Transport\TransportData $transportData
     */
    public function createFromTransport(BaseTransport $transport): BaseTransportData
    {
        $transportData = new TransportData();
        $this->fillFromTransport($transportData, $transport);

        return $transportData;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Transport\TransportData $transportData
     */
    protected function fillNew(BaseTransportData $transportData)
    {
        parent::fillNew($transportData);
        $transportData->type = Transport::TYPE_DEFAULT;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Transport\TransportData $transportData
     * @param \Shopsys\ShopBundle\Model\Transport\Transport $transport
     */
    protected function fillFromTransport(BaseTransportData $transportData, BaseTransport $transport)
    {
        parent::fillFromTransport($transportData, $transport);
        $transportData->type = $transport->getType();
    }
}

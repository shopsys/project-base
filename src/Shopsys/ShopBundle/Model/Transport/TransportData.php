<?php

namespace Shopsys\ShopBundle\Model\Transport;

use Shopsys\FrameworkBundle\Model\Transport\TransportData as BaseTransportData;

class TransportData extends BaseTransportData
{
    /**
     * @var string
     */
    public $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = Transport::TYPE_DEFAULT;
    }
}

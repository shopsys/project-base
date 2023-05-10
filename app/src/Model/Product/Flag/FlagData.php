<?php

declare(strict_types=1);

namespace App\Model\Product\Flag;

use Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\UrlListData;
use Shopsys\FrameworkBundle\Model\Product\Flag\FlagData as BaseFlagData;

class FlagData extends BaseFlagData
{
    /**
     * @var string|null
     */
    public $akeneoCode;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\UrlListData
     */
    public $urls;

    public function __construct()
    {
        parent::__construct();

        $this->rgbColor = '';
        $this->urls = new UrlListData();
    }
}

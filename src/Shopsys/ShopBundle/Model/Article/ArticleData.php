<?php

namespace Shopsys\ShopBundle\Model\Article;

use DateTime;
use Shopsys\FrameworkBundle\Model\Article\ArticleData as BaseArticleData;

class ArticleData extends BaseArticleData
{
    /**
     * @var \DateTime|null
     */
    public $createdAt;

    /**
     * @var array
     */
    public $products;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->products = [];
        parent::__construct();
    }
}

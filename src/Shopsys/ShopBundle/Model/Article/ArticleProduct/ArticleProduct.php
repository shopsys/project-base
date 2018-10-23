<?php

namespace Shopsys\ShopBundle\Model\Article\ArticleProduct;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\ShopBundle\Model\Article\Article;
use Shopsys\ShopBundle\Model\Product\Product;

/**
 * @ORM\Entity
 * @ORM\Table(name="article_products")
 */
class ArticleProduct
{
    /**
     * @var \Shopsys\ShopBundle\Model\Article\Article
     *
     * @ORM\ManyToOne(targetEntity="\Shopsys\ShopBundle\Model\Article\Article")
     * @ORM\JoinColumn(nullable=false, name="article_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $article;

    /**
     * @var \Shopsys\ShopBundle\Model\Product\Product
     *
     * @ORM\ManyToOne(targetEntity="\Shopsys\ShopBundle\Model\Product\Product")
     * @ORM\JoinColumn(nullable=false, name="product_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $product;

    /**
     * @param \Shopsys\ShopBundle\Model\Article\Article $article
     * @param \Shopsys\ShopBundle\Model\Product\Product $product
     */
    public function __construct(Article $article, Product $product)
    {
        $this->article = $article;
        $this->product = $product;
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Article\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Product\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}

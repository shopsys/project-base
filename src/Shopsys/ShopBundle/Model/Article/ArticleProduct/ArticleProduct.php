<?php

namespace Shopsys\ShopBundle\Model\Article\ArticleProduct;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\ShopBundle\Model\Article\Article;
use Shopsys\ShopBundle\Model\Product\Product;

/**
 * @ORM\Table(name="article_products")
 * @ORM\Entity
 */
class ArticleProduct
{
    /**
     * @var \Shopsys\ShopBundle\Model\Article\Article
     *
     * @ORM\ManyToOne(targetEntity="\Shopsys\ShopBundle\Model\Article\Article")
     * @ORM\JoinColumn(name="article_id", nullable=false, referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Id
     */
    private $article;

    /**
     * @var \Shopsys\ShopBundle\Model\Product\Product
     *
     * @ORM\ManyToOne(targetEntity="\Shopsys\ShopBundle\Model\Product\Product")
     * @ORM\JoinColumn(name="product_id", nullable=false, referencedColumnName="id", onDelete="CASCADE")
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

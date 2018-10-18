<?php

namespace Shopsys\ShopBundle\Model\Article\ArticleProduct;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\ShopBundle\Model\Article\Article;

class ArticleProductRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProductRepository
     */
    public function getArticleProductRepository()
    {
        return $this->entityManager->getRepository(ArticleProduct::class);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Article\Article $article
     * @return \Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProduct[]
     */
    public function getArticleProductsByArticle(Article $article)
    {
        return $this->getArticleProductRepository()->findBy(['article' => $article]);
    }
}

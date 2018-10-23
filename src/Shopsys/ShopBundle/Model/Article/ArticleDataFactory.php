<?php

namespace Shopsys\ShopBundle\Model\Article;

use DateTime;
use Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrlFacade;
use Shopsys\FrameworkBundle\Model\Article\Article as BaseArticle;
use Shopsys\FrameworkBundle\Model\Article\ArticleData as BaseArticleData;
use Shopsys\FrameworkBundle\Model\Article\ArticleDataFactory as BaseArticleDataFactory;
use Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProductRepository;

class ArticleDataFactory extends BaseArticleDataFactory
{
    /**
     * @var \Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProductRepository
     */
    private $articleProductRepository;

    /**
     * @param \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrlFacade $friendlyUrlFacade
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Component\Domain\AdminDomainTabsFacade $adminDomainTabsFacade
     * @param \Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProductRepository $articleProductRepository
     */
    public function __construct(
        FriendlyUrlFacade $friendlyUrlFacade,
        Domain $domain,
        AdminDomainTabsFacade $adminDomainTabsFacade,
        ArticleProductRepository $articleProductRepository
    ) {
        $this->articleProductRepository = $articleProductRepository;
        parent::__construct($friendlyUrlFacade, $domain, $adminDomainTabsFacade);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Article\Article $article
     * @return \Shopsys\ShopBundle\Model\Article\ArticleData
     */
    public function createFromArticle(BaseArticle $article): BaseArticleData
    {
        $articleData = new ArticleData();
        $this->fillFromArticle($articleData, $article);

        $articleData->createdAt = $article->getCreatedAt() ?? new DateTime();
        $articleData->products = $this->getProductsByArticle($article);

        return $articleData;
    }

    /**
     * @return \Shopsys\ShopBundle\Model\Article\ArticleData
     */
    public function create(): BaseArticleData
    {
        $articleData = new ArticleData();
        $this->fillNew($articleData);

        return $articleData;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Article\Article $article
     * @return \Shopsys\ShopBundle\Model\Product\Product[]
     */
    private function getProductsByArticle(Article $article)
    {
        $articleProducts = $this->articleProductRepository->getArticleProductsByArticle($article);

        $products = [];
        foreach ($articleProducts as $articleProduct) {
            $products[] = $articleProduct->getProduct();
        }

        return $products;
    }
}

<?php

namespace Shopsys\ShopBundle\Model\Article;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrlFacade;
use Shopsys\FrameworkBundle\Model\Article\ArticleData;
use Shopsys\FrameworkBundle\Model\Article\ArticleFacade as BaseArticleFacade;
use Shopsys\FrameworkBundle\Model\Article\ArticleFactoryInterface;
use Shopsys\FrameworkBundle\Model\Article\ArticleRepository;
use Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProduct;
use Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProductRepository;

class ArticleFacade extends BaseArticleFacade
{
    /**
     * @var \Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProductRepository
     */
    private $articleProductRepository;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Shopsys\FrameworkBundle\Model\Article\ArticleRepository $articleRepository
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrlFacade $friendlyUrlFacade
     * @param \Shopsys\FrameworkBundle\Model\Article\ArticleFactoryInterface $articleFactory
     * @param \Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProductRepository $articleProductRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        ArticleRepository $articleRepository,
        Domain $domain,
        FriendlyUrlFacade $friendlyUrlFacade,
        ArticleFactoryInterface $articleFactory,
        ArticleProductRepository $articleProductRepository
    ){
        parent::__construct($em, $articleRepository, $domain, $friendlyUrlFacade, $articleFactory);
        $this->articleProductRepository = $articleProductRepository;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Article\ArticleData $articleData
     * @return \Shopsys\FrameworkBundle\Model\Article\Article
     */
    public function create(ArticleData $articleData)
    {
        $article = parent::create($articleData);
        $products = $articleData->products;
        $this->refreshArticleProducts($article, $products);

        return $article;
    }

    /**
     * @param int $articleId
     * @param \Shopsys\ShopBundle\Model\Article\ArticleData $articleData
     * @return \Shopsys\FrameworkBundle\Model\Article\Article
     */
    public function edit($articleId, ArticleData $articleData)
    {
        $products = $articleData->products;
        $article = parent::edit($articleId, $articleData);
        $this->refreshArticleProducts($article, $products);

        return $article;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Article\Article $article
     * @param \Shopsys\ShopBundle\Model\Product\Product[] $products
     */
    private function refreshArticleProducts(Article $article, array $products)
    {
        $oldArticleProducts = $this->articleProductRepository->getArticleProductsByArticle($article);

        foreach ($oldArticleProducts as $articleProduct) {
            $this->em->remove($articleProduct);
        }
        $this->em->flush($oldArticleProducts);

        $newArticleProducts = [];
        foreach ($products as $product) {
            $articleProduct = new ArticleProduct($article, $product);
            $this->em->persist($articleProduct);
            $newArticleProducts[] = $articleProduct;
        }
        $this->em->flush($newArticleProducts);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Article\Article $article
     * @return \Shopsys\ShopBundle\Model\Product\Product[]
     */
    public function getProductsByArticle(Article $article)
    {
        $articleProducts = $this->articleProductRepository->getArticleProductsByArticle($article);

        $products = [];
        foreach ($articleProducts as $articleProduct) {
            $products[] = $articleProduct->getProduct();
        }

        return $products;
    }
}

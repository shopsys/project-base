<?php

declare(strict_types = 1);

namespace Shopsys\ShopBundle\Model\Product\LastVisited;

use DateTime;
use Shopsys\ShopBundle\Model\Product\ProductOnCurrentDomainFacade;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class LastVisitedProductFacade
{
    private const LAST_VISITED_PRODUCTS_COOKIES_IDS_DELIMITER = ',';

    private const LAST_VISITED_PRODUCTS_COOKIES_IDENTIFIER = 'lastVisitedProductsIds';

    private const LAST_VISITED_PRODUCTS_COOKIES_EXPIRE_YEARS = '1';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Shopsys\ShopBundle\Model\Product\ProductOnCurrentDomainFacade
     */
    private $productOnCurrentDomainFacade;

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $request
     * @param \Shopsys\ShopBundle\Model\Product\ProductOnCurrentDomainFacade $productOnCurrentDomainFacade
     */
    public function __construct(
        RequestStack $request,
        ProductOnCurrentDomainFacade $productOnCurrentDomainFacade
    ) {
        $this->request = $request;
        $this->productOnCurrentDomainFacade = $productOnCurrentDomainFacade;
    }

    /**
     * @param int $productId
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function updateLastVisitedProductsIds(int $productId, Response $response)
    {
        $lastVisitedProductsIdsString = $this->request->getMasterRequest()->cookies->get(
            self::LAST_VISITED_PRODUCTS_COOKIES_IDENTIFIER,
            ''
        );

        $lastVisitedProductsIds = explode(
            self::LAST_VISITED_PRODUCTS_COOKIES_IDS_DELIMITER,
            $lastVisitedProductsIdsString
        );

        $lastVisitedProductsIds = array_filter(array_map('intval', $lastVisitedProductsIds));

        $indexOfProductIdIfAlreadyVisited = array_search($productId, $lastVisitedProductsIds, true);
        if ($indexOfProductIdIfAlreadyVisited !== false) {
            unset($lastVisitedProductsIds[$indexOfProductIdIfAlreadyVisited]);
        }

        array_unshift($lastVisitedProductsIds, $productId);

        $cookie = new Cookie(
            self::LAST_VISITED_PRODUCTS_COOKIES_IDENTIFIER,
            implode(self::LAST_VISITED_PRODUCTS_COOKIES_IDS_DELIMITER, $lastVisitedProductsIds),
            new DateTime('+' . self::LAST_VISITED_PRODUCTS_COOKIES_EXPIRE_YEARS . ' years')
        );

        $response->headers->setCookie($cookie);
    }

    /**
     * @param int $limit
     * @return \Shopsys\ShopBundle\Model\Product\Product[]
     */
    public function getLastVisitedProducts(int $limit)
    {
        $lastVisitedProductsIdsString = $this->request->getMasterRequest()->cookies->get(
            self::LAST_VISITED_PRODUCTS_COOKIES_IDENTIFIER,
            ''
        );

        $lastVisitedProductsIds = array_filter(explode(
            self::LAST_VISITED_PRODUCTS_COOKIES_IDS_DELIMITER,
            $lastVisitedProductsIdsString
        ));

        $limitedLastVisitedProductsIds = array_slice($lastVisitedProductsIds, 0, $limit);

        return $this->productOnCurrentDomainFacade->getVisibleProductsByIds($limitedLastVisitedProductsIds);
    }
}

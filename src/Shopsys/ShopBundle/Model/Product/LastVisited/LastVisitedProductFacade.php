<?php

declare(strict_types = 1);

namespace Shopsys\ShopBundle\Model\Product\LastVisited;

use DateTime;
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
     * @param \Symfony\Component\HttpFoundation\RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
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
}

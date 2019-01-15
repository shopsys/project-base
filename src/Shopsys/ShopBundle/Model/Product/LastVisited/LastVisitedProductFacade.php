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
        $oldLastVisitedProductsIdsString = $this->request->getMasterRequest()->cookies->get(
            self::LAST_VISITED_PRODUCTS_COOKIES_IDENTIFIER,
            ''
        );

        $oldLastVisitedProductsIds = explode(
            self::LAST_VISITED_PRODUCTS_COOKIES_IDS_DELIMITER,
            $oldLastVisitedProductsIdsString
        );

        $newLastVisitedProductsIds = $oldLastVisitedProductsIds;

        $newLastVisitedProductsIds[] = $productId;
        $newLastVisitedProductsIds = array_unique(array_filter(array_map('intval', $newLastVisitedProductsIds)));

        $cookie = new Cookie(
            self::LAST_VISITED_PRODUCTS_COOKIES_IDENTIFIER,
            implode(self::LAST_VISITED_PRODUCTS_COOKIES_IDS_DELIMITER, $newLastVisitedProductsIds),
            new DateTime('+' . self::LAST_VISITED_PRODUCTS_COOKIES_EXPIRE_YEARS . ' years')
        );

        $response->headers->setCookie($cookie);
    }
}

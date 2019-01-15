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

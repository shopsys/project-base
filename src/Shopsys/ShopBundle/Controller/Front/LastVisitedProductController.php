<?php

declare(strict_types = 1);

namespace Shopsys\ShopBundle\Controller\Front;

use Shopsys\ShopBundle\Model\Product\LastVisited\LastVisitedProductFacade;

class LastVisitedProductController extends FrontBaseController
{
    private const BOX_MAX_VISITED_PRODUCTS_COUNT = 5;

    /**
     * @var \Shopsys\ShopBundle\Model\Product\LastVisited\LastVisitedProductFacade
     */
    private $lastVisitedProductFacade;

    /**
     * @param \Shopsys\ShopBundle\Model\Product\LastVisited\LastVisitedProductFacade $lastVisitedProductFacade
     */
    public function __construct(LastVisitedProductFacade $lastVisitedProductFacade)
    {
        $this->lastVisitedProductFacade = $lastVisitedProductFacade;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function boxAction()
    {
        $lastVisitedProducts = $this->lastVisitedProductFacade->getLastVisitedProducts(self::BOX_MAX_VISITED_PRODUCTS_COUNT);

        return $this->render('@ShopsysShop/Front/Content/LastVisitedProduct/box.html.twig', [
            'lastVisitedProducts' => $lastVisitedProducts,
        ]);
    }
}

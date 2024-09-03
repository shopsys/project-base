<?php

declare(strict_types=1);

namespace App\FrontendApi\Resolver\Store;

use Overblog\GraphQLBundle\Resolver\ResolverMap;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrlFacade;
use Shopsys\FrameworkBundle\Model\Store\Store;

class StoreResolverMap extends ResolverMap
{
    /**
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrlFacade $friendlyUrlFacade
     */
    public function __construct(
        private readonly Domain $domain,
        private readonly FriendlyUrlFacade $friendlyUrlFacade,
    ) {
    }

    /**
     * @return array
     */
    protected function map(): array
    {
        return [
            'Store' => [
                'slug' => fn (Store $store): string => $this->getSlug($store),
            ],
        ];
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Store\Store $store
     * @return string
     */
    private function getSlug(Store $store): string
    {
        $friendlyUrlSlug = $this->friendlyUrlFacade->getMainFriendlyUrlSlug(
            $this->domain->getId(),
            'front_stores_detail',
            $store->getId(),
        );

        return '/' . $friendlyUrlSlug;
    }
}

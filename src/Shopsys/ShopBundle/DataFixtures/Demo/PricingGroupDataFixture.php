<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\Persistence\ObjectManager;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupData;
use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade;
use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupSettingFacade;

class PricingGroupDataFixture extends AbstractReferenceFixture
{
    public const PRICING_GROUP_ORDINARY_DOMAIN = 'pricing_group_ordinary_domain';
    public const PRICING_GROUP_PARTNER_DOMAIN = 'pricing_group_partner_domain';
    public const PRICING_GROUP_VIP_DOMAIN = 'pricing_group_vip_domain';

    public const PRICING_GROUP_ORDINARY_DOMAIN_1 = 'pricing_group_ordinary_domain_1';

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade
     */
    protected $pricingGroupFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupDataFactoryInterface
     */
    protected $pricingGroupDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupSettingFacade
     */
    protected $pricingGroupSettingFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade $pricingGroupFacade
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupDataFactoryInterface $pricingGroupDataFactory
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupSettingFacade $pricingGroupSettingFacade
     */
    public function __construct(
        PricingGroupFacade $pricingGroupFacade,
        PricingGroupDataFactoryInterface $pricingGroupDataFactory,
        Domain $domain,
        PricingGroupSettingFacade $pricingGroupSettingFacade
    ) {
        $this->pricingGroupFacade = $pricingGroupFacade;
        $this->pricingGroupDataFactory = $pricingGroupDataFactory;
        $this->domain = $domain;
        $this->pricingGroupSettingFacade = $pricingGroupSettingFacade;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->domain->getAll() as $domainConfig) {
            $domainId = $domainConfig->getId();
            $locale = $domainConfig->getLocale();

            $pricingGroupData = $this->pricingGroupDataFactory->create();

            $this->editDefaultPricingGroupOnDomain($domainConfig, $pricingGroupData);

            if ($domainId !== 2) {
                $pricingGroupData->name = t('Partner', [], 'dataFixtures', $locale);
                $this->createPricingGroup($pricingGroupData, $domainId, self::PRICING_GROUP_PARTNER_DOMAIN);
            }

            $pricingGroupData->name = t('VIP customer', [], 'dataFixtures', $locale);
            $this->createPricingGroup($pricingGroupData, $domainId, self::PRICING_GROUP_VIP_DOMAIN);
        }
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupData $pricingGroupData
     * @param int $domainId
     * @param string $referenceName
     */
    protected function createPricingGroup(
        PricingGroupData $pricingGroupData,
        int $domainId,
        string $referenceName
    ): void {
        $pricingGroup = $this->pricingGroupFacade->create($pricingGroupData, $domainId);
        $this->addReferenceForDomain($referenceName, $pricingGroup, $domainId);
    }

    /**
     * The default pricing group for domain 1 is created in database migration.
     * @see \Shopsys\FrameworkBundle\Migrations\Version20180603135346
     *
     * The default pricing groups for the other domains are created during build (in "domains-data-create" phing target).
     * @see \Shopsys\FrameworkBundle\Component\Domain\DomainDataCreator
     *
     * @param \Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig $domainConfig
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupData $pricingGroupData
     */
    protected function editDefaultPricingGroupOnDomain(DomainConfig $domainConfig, PricingGroupData $pricingGroupData): void
    {
        $pricingGroupData->name = t('Ordinary customer', [], 'dataFixtures', $domainConfig->getLocale());
        $defaultPricingGroupOnDomain = $this->pricingGroupSettingFacade->getDefaultPricingGroupByDomainId($domainConfig->getId());
        $this->pricingGroupFacade->edit($defaultPricingGroupOnDomain->getId(), $pricingGroupData);
        $this->addReferenceForDomain(self::PRICING_GROUP_ORDINARY_DOMAIN, $defaultPricingGroupOnDomain, $domainConfig->getId());
    }
}

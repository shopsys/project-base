<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\DataFixtures\Demo;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;
use Shopsys\FrameworkBundle\Model\Transport\TransportData;
use Shopsys\FrameworkBundle\Model\Transport\TransportDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Transport\TransportFacade;

class TransportDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface
{
    public const TRANSPORT_CZECH_POST = 'transport_cp';
    public const TRANSPORT_PPL = 'transport_ppl';
    public const TRANSPORT_PERSONAL = 'transport_personal';

    /** @var \Shopsys\FrameworkBundle\Model\Transport\TransportFacade */
    protected $transportFacade;

    /**
     * @var \Shopsys\ShopBundle\Model\Transport\TransportDataFactory
     */
    protected $transportDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    protected $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade
     */
    protected $currencyFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\TransportFacade $transportFacade
     * @param \Shopsys\ShopBundle\Model\Transport\TransportDataFactory $transportDataFactory
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade $currencyFacade
     */
    public function __construct(
        TransportFacade $transportFacade,
        TransportDataFactoryInterface $transportDataFactory,
        Domain $domain,
        CurrencyFacade $currencyFacade
    ) {
        $this->transportFacade = $transportFacade;
        $this->transportDataFactory = $transportDataFactory;
        $this->domain = $domain;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $transportData = $this->transportDataFactory->create();

        foreach ($this->domain->getAllLocales() as $locale) {
            $transportData->name[$locale] = t('Czech post', [], 'dataFixtures', $locale);
        }

        foreach ($this->domain->getAllIncludingDomainConfigsWithoutDataCreated() as $domain) {
            $currency = $this->currencyFacade->getDomainDefaultCurrencyByDomainId($domain->getId());
            $transportData->pricesByCurrencyId[$currency->getId()] = Money::create((string)(99.95 / $currency->getExchangeRate()));
        }

        $transportData->vat = $this->getReference(VatDataFixture::VAT_HIGH);
        $this->createTransport(self::TRANSPORT_CZECH_POST, $transportData);

        $transportData = $this->transportDataFactory->create();

        foreach ($this->domain->getAllLocales() as $locale) {
            $transportData->name[$locale] = t('PPL', [], 'dataFixtures', $locale);
        }

        foreach ($this->domain->getAllIncludingDomainConfigsWithoutDataCreated() as $domain) {
            $currency = $this->currencyFacade->getDomainDefaultCurrencyByDomainId($domain->getId());
            $transportData->pricesByCurrencyId[$currency->getId()] = Money::create((string)(199.95 / $currency->getExchangeRate()));
        }

        $transportData->vat = $this->getReference(VatDataFixture::VAT_HIGH);
        $this->createTransport(self::TRANSPORT_PPL, $transportData);

        $transportData = $this->transportDataFactory->create();

        foreach ($this->domain->getAllLocales() as $locale) {
            $transportData->name[$locale] = t('Personal collection', [], 'dataFixtures', $locale);
        }

        foreach ($this->domain->getAllIncludingDomainConfigsWithoutDataCreated() as $domain) {
            $currency = $this->currencyFacade->getDomainDefaultCurrencyByDomainId($domain->getId());
            $transportData->pricesByCurrencyId[$currency->getId()] = Money::zero();
        }

        foreach ($this->domain->getAllLocales() as $locale) {
            $transportData->description[$locale] = t('You will be welcomed by friendly staff!', [], 'dataFixtures', $locale);
        }

        foreach ($this->domain->getAllLocales() as $locale) {
            $transportData->instructions[$locale] = t('We are looking forward to your visit.', [], 'dataFixtures', $locale);
        }

        $transportData->vat = $this->getReference(VatDataFixture::VAT_ZERO);
        $this->createTransport(self::TRANSPORT_PERSONAL, $transportData);
    }

    /**
     * @param string $referenceName
     * @param \Shopsys\ShopBundle\Model\Transport\TransportData $transportData
     */
    protected function createTransport($referenceName, TransportData $transportData)
    {
        $transport = $this->transportFacade->create($transportData);
        $this->addReference($referenceName, $transport);
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            VatDataFixture::class,
            CurrencyDataFixture::class,
            SettingValueDataFixture::class,
        ];
    }
}

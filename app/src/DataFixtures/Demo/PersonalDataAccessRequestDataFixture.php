<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo;

use Doctrine\Persistence\ObjectManager;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Model\PersonalData\PersonalDataAccessRequestDataFactory;
use Shopsys\FrameworkBundle\Model\PersonalData\PersonalDataAccessRequestFacade;

class PersonalDataAccessRequestDataFixture extends AbstractReferenceFixture
{
    public const string REFERENCE_ACCESS_DISPLAY_REQUEST = 'reference_access_display_request';
    public const string REFERENCE_ACCESS_EXPORT_REQUEST = 'reference_access_export_request';

    /**
     * @param \Shopsys\FrameworkBundle\Model\PersonalData\PersonalDataAccessRequestFacade $personalDataFacade
     * @param \Shopsys\FrameworkBundle\Model\PersonalData\PersonalDataAccessRequestDataFactory $personalDataFactory
     */
    public function __construct(
        private readonly PersonalDataAccessRequestFacade $personalDataFacade,
        private readonly PersonalDataAccessRequestDataFactory $personalDataFactory,
    ) {
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $domainId = $this->domainsForDataFixtureProvider->getFirstAllowedDomainConfig()->getId();

        $personalDataAccessRequestData = $this->personalDataFactory->createForDisplay();
        $personalDataAccessRequestData->domainId = $domainId;
        $personalDataAccessRequestData->email = 'no-reply@shopsys.com';
        $personalDataAccessRequestData->hash = 'UrSqiLmCK0cdGfBuwRza';

        $personalDataAccessRequest = $this->personalDataFacade->createPersonalDataAccessRequest(
            $personalDataAccessRequestData,
            $domainId,
        );

        $this->addReference(self::REFERENCE_ACCESS_DISPLAY_REQUEST, $personalDataAccessRequest);

        $personalDataAccessRequestData = $this->personalDataFactory->createForExport();
        $personalDataAccessRequestData->domainId = $domainId;
        $personalDataAccessRequestData->email = 'no-reply@shopsys.com';
        $personalDataAccessRequestData->hash = 'UrSqiLmCK0cdGfBuwRza';

        $personalDataAccessRequest = $this->personalDataFacade->createPersonalDataAccessRequest(
            $personalDataAccessRequestData,
            $domainId,
        );

        $this->addReference(self::REFERENCE_ACCESS_EXPORT_REQUEST, $personalDataAccessRequest);
    }
}

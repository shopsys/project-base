<?php

declare(strict_types=1);

namespace App\DataFixtures\Performance;

use App\DataFixtures\Demo\CountryDataFixture;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator as Faker;
use Shopsys\FrameworkBundle\Component\Console\ProgressBarFactory;
use Shopsys\FrameworkBundle\Component\DataFixture\PersistentReferenceFacade;
use Shopsys\FrameworkBundle\Component\Doctrine\SqlLoggerFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerUserDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\CustomerUserFacade;
use Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface;
use Shopsys\FrameworkBundle\Model\Customer\UserDataFactoryInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserDataFixture
{
    public const FIRST_PERFORMANCE_USER = 'first_performance_user';

    /**
     * @var int
     */
    private $userCountPerDomain;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Domain\Domain
     */
    private $domain;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Doctrine\SqlLoggerFacade
     */
    private $sqlLoggerFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerUserFacade
     */
    private $customerEditFacade;

    /**
     * @var \App\Model\Customer\UserDataFactory
     */
    private $userDataFactory;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var \Shopsys\FrameworkBundle\Component\DataFixture\PersistentReferenceFacade
     */
    private $persistentReferenceFacade;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Console\ProgressBarFactory
     */
    private $progressBarFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\CustomerUserDataFactoryInterface
     */
    private $customerDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface
     */
    private $billingAddressDataFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface
     */
    private $deliveryAddressDataFactory;

    /**
     * @param int $userCountPerDomain
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Component\Doctrine\SqlLoggerFacade $sqlLoggerFacade
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerUserFacade $customerEditFacade
     * @param \App\Model\Customer\UserDataFactory $userDataFactory
     * @param \Faker\Generator $faker
     * @param \Shopsys\FrameworkBundle\Component\DataFixture\PersistentReferenceFacade $persistentReferenceFacade
     * @param \Shopsys\FrameworkBundle\Component\Console\ProgressBarFactory $progressBarFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\CustomerUserDataFactoryInterface $customerDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\BillingAddressDataFactoryInterface $billingAddressDataFactory
     * @param \Shopsys\FrameworkBundle\Model\Customer\DeliveryAddressDataFactoryInterface $deliveryAddressDataFactory
     */
    public function __construct(
        $userCountPerDomain,
        EntityManagerInterface $em,
        Domain $domain,
        SqlLoggerFacade $sqlLoggerFacade,
        CustomerUserFacade $customerEditFacade,
        UserDataFactoryInterface $userDataFactory,
        Faker $faker,
        PersistentReferenceFacade $persistentReferenceFacade,
        ProgressBarFactory $progressBarFactory,
        CustomerUserDataFactoryInterface $customerDataFactory,
        BillingAddressDataFactoryInterface $billingAddressDataFactory,
        DeliveryAddressDataFactoryInterface $deliveryAddressDataFactory
    ) {
        $this->em = $em;
        $this->domain = $domain;
        $this->sqlLoggerFacade = $sqlLoggerFacade;
        $this->customerEditFacade = $customerEditFacade;
        $this->userDataFactory = $userDataFactory;
        $this->faker = $faker;
        $this->persistentReferenceFacade = $persistentReferenceFacade;
        $this->userCountPerDomain = $userCountPerDomain;
        $this->progressBarFactory = $progressBarFactory;
        $this->customerDataFactory = $customerDataFactory;
        $this->billingAddressDataFactory = $billingAddressDataFactory;
        $this->deliveryAddressDataFactory = $deliveryAddressDataFactory;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function load(OutputInterface $output)
    {
        // Sql logging during mass data import makes memory leak
        $this->sqlLoggerFacade->temporarilyDisableLogging();
        $domains = $this->domain->getAll();

        $progressBar = $this->progressBarFactory->create($output, count($domains) * $this->userCountPerDomain);

        $isFirstUser = true;

        foreach ($domains as $domainConfig) {
            for ($i = 0; $i < $this->userCountPerDomain; $i++) {
                $user = $this->createCustomerOnDomain($domainConfig->getId(), $i);
                $progressBar->advance();

                if ($isFirstUser) {
                    $this->persistentReferenceFacade->persistReference(self::FIRST_PERFORMANCE_USER, $user);
                    $isFirstUser = false;
                }

                $this->em->clear();
            }
        }

        $this->sqlLoggerFacade->reenableLogging();
    }

    /**
     * @param int $domainId
     * @param int $userNumber
     * @return \App\Model\Customer\User
     */
    private function createCustomerOnDomain($domainId, $userNumber)
    {
        $customerData = $this->getRandomCustomerDataByDomainId($domainId, $userNumber);

        /** @var \App\Model\Customer\User $user */
        $user = $this->customerEditFacade->create($customerData);

        return $user;
    }

    /**
     * @param int $domainId
     * @param int $userNumber
     * @return \Shopsys\FrameworkBundle\Model\Customer\CustomerUserData
     */
    private function getRandomCustomerDataByDomainId($domainId, $userNumber)
    {
        $customerData = $this->customerDataFactory->create();

        $country = $this->persistentReferenceFacade->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC);

        $userData = $this->userDataFactory->createForDomainId($domainId);
        $userData->firstName = $this->faker->firstName;
        $userData->lastName = $this->faker->lastName;
        $userData->email = $userNumber . '.' . $this->faker->safeEmail;
        $userData->password = $this->faker->password;
        $userData->domainId = $domainId;
        $userData->createdAt = $this->faker->dateTimeBetween('-1 year', 'now');
        $userData->telephone = $this->faker->phoneNumber;

        $customerData->userData = $userData;

        $billingAddressData = $this->billingAddressDataFactory->create();
        $billingAddressData->companyCustomer = $this->faker->boolean();
        if ($billingAddressData->companyCustomer === true) {
            $billingAddressData->companyName = $this->faker->company;
            $billingAddressData->companyNumber = (string)$this->faker->randomNumber(6);
            $billingAddressData->companyTaxNumber = (string)$this->faker->randomNumber(6);
        }
        $billingAddressData->street = $this->faker->streetAddress;
        $billingAddressData->city = $this->faker->city;
        $billingAddressData->postcode = $this->faker->postcode;
        $billingAddressData->country = $country;
        $customerData->billingAddressData = $billingAddressData;

        $deliveryAddressData = $this->deliveryAddressDataFactory->create();
        $deliveryAddressData->addressFilled = true;
        $deliveryAddressData->city = $this->faker->city;
        $deliveryAddressData->companyName = $this->faker->company;
        $deliveryAddressData->firstName = $this->faker->firstName;
        $deliveryAddressData->lastName = $this->faker->lastName;
        $deliveryAddressData->postcode = $this->faker->postcode;
        $deliveryAddressData->country = $country;
        $deliveryAddressData->street = $this->faker->streetAddress;
        $deliveryAddressData->telephone = $this->faker->phoneNumber;
        $customerData->deliveryAddressData = $deliveryAddressData;

        return $customerData;
    }
}

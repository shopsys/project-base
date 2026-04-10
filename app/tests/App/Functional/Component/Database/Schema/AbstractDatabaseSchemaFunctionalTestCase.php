<?php

declare(strict_types=1);

namespace Tests\App\Functional\Component\Database\Schema;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;
use Override;
use Shopsys\FrameworkBundle\Component\Doctrine\DatabaseSchemaFacade;
use Tests\App\Test\Doctrine\AttributeMappedEntityHelper;
use Tests\App\Test\TransactionFunctionalTestCase;

abstract class AbstractDatabaseSchemaFunctionalTestCase extends TransactionFunctionalTestCase
{
    protected const string TEST_ENTITY_NAMESPACE_PREFIX = 'Tests\\App\\Functional\\Component\\Database\\Schema\\Model\\';

    /**
     * @inject
     */
    protected DatabaseSchemaFacade $databaseSchemaFacade;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->skipTestIfNotRunningInMonorepo();

        $this->databaseSchemaFacade->dropSchemaIfExists('public');
        $this->databaseSchemaFacade->createSchema('public');

        $this->registerTestEntities();
        $metadata = $this->getMetadata();

        $schemaTool = new SchemaTool($this->em);
        $schemaTool->updateSchema($metadata);
    }

    private function registerTestEntities(): void
    {
        AttributeMappedEntityHelper::register(
            $this->em,
            [__DIR__ . '/Model'],
            static::TEST_ENTITY_NAMESPACE_PREFIX,
        );
    }

    /**
     * @return array<int, \Doctrine\Persistence\Mapping\ClassMetadata>
     */
    private function getMetadata(): array
    {
        return array_values(array_filter(
            $this->em->getMetadataFactory()->getAllMetadata(),
            fn (ClassMetadata $classMetadata): bool => str_starts_with($classMetadata->getName(), static::TEST_ENTITY_NAMESPACE_PREFIX),
        ));
    }
}

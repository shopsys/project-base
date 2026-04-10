<?php

declare(strict_types=1);

namespace Tests\App\Functional\Component\Database\Schema;

use Shopsys\McpBundle\Component\Database\Schema\AllowedDatabaseTablesProvider;
use Tests\App\Functional\Component\Database\Schema\Model\NonQueryableRelationEntity;
use Tests\App\Functional\Component\Database\Schema\Model\QueryableEntity;
use Tests\App\Functional\Component\Database\Schema\Model\QueryableRelationEntity;

class AllowedDatabaseTablesProviderTest extends AbstractDatabaseSchemaFunctionalTestCase
{
    /**
     * @inject
     */
    private AllowedDatabaseTablesProvider $allowedDatabaseTablesProvider;

    public function testGetAllowedTableNamesReturnsAllowlistedTables(): void
    {
        $allowedTableNames = $this->allowedDatabaseTablesProvider->getAllowedTableNames();

        $this->assertContains(QueryableEntity::TABLE_NAME, $allowedTableNames);
        $this->assertContains(QueryableRelationEntity::TABLE_NAME, $allowedTableNames);
        $this->assertNotContains(NonQueryableRelationEntity::TABLE_NAME, $allowedTableNames);
    }

    public function testGetAllowedClassMetadataByTableNamesReturnsOnlyRequestedAllowlistedTables(): void
    {
        $allowedClassMetadataByTableNames = $this->allowedDatabaseTablesProvider->getAllowedClassMetadataByTableNames([
            QueryableRelationEntity::TABLE_NAME,
            NonQueryableRelationEntity::TABLE_NAME,
        ]);

        $this->assertSame([QueryableRelationEntity::TABLE_NAME], array_keys($allowedClassMetadataByTableNames));
    }

    public function testGetAllowedClassMetadataByTableNamesReturnsNoTablesForEmptyRequestedTableNames(): void
    {
        $allowedClassMetadataByTableNames = $this->allowedDatabaseTablesProvider->getAllowedClassMetadataByTableNames([]);

        $this->assertSame([], $allowedClassMetadataByTableNames);
    }
}

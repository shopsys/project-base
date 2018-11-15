<?php

namespace Shopsys\ShopBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Shopsys\MigrationBundle\Component\Doctrine\Migrations\AbstractMigration;

class Version20181115122917 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->sql('
            CREATE TABLE article_products (
                article_id INT NOT NULL,
                product_id INT NOT NULL,
                PRIMARY KEY(article_id, product_id)
            )');
        $this->sql('CREATE INDEX IDX_E652AE317294869C ON article_products (article_id)');
        $this->sql('CREATE INDEX IDX_E652AE314584665A ON article_products (product_id)');
        $this->sql('
            ALTER TABLE
                article_products
            ADD
                CONSTRAINT FK_E652AE317294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->sql('
            ALTER TABLE
                article_products
            ADD
                CONSTRAINT FK_E652AE314584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}

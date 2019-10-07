<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Shopsys\MigrationBundle\Component\Doctrine\Migrations\AbstractMigration;

class Version20191002130709 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->sql('CREATE TABLE companies (id SERIAL NOT NULL, billing_address_id INT NOT NULL, PRIMARY KEY(id))');
        $this->sql('CREATE UNIQUE INDEX UNIQ_8244AA3A79D0C0E4 ON companies (billing_address_id)');
        $this->sql('
            ALTER TABLE
                companies
            ADD
                CONSTRAINT FK_8244AA3A79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES billing_addresses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->sql('ALTER TABLE delivery_addresses ADD company_id INT DEFAULT NULL');
        $this->sql('
            ALTER TABLE
                delivery_addresses
            ADD
                CONSTRAINT FK_2BAF3984979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->sql('CREATE INDEX IDX_2BAF3984979B1AD6 ON delivery_addresses (company_id)');
        $this->sql('ALTER TABLE users ADD company_id INT DEFAULT NULL');
        $this->sql('
            ALTER TABLE
                users
            ADD
                CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->sql('CREATE INDEX IDX_1483A5E9979B1AD6 ON users (company_id)');
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function down(Schema $schema): void
    {
    }
}

<?php

namespace Shopsys\ShopBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Shopsys\MigrationBundle\Component\Doctrine\Migrations\AbstractMigration;

class Version20190408114534 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->sql('
            CREATE TABLE pick_up_place (
                id SERIAL NOT NULL,
                transport_type VARCHAR(255) NOT NULL,
                place_id INT NOT NULL,
                country_code VARCHAR(10) NOT NULL,
                city VARCHAR(250) NOT NULL,
                street VARCHAR(250) NOT NULL,
                post_code VARCHAR(30) NOT NULL,
                gps_latitude DOUBLE PRECISION NOT NULL,
                gps_longitude DOUBLE PRECISION NOT NULL,
                name VARCHAR(250) NOT NULL,
                description TEXT NOT NULL,
                active BOOLEAN NOT NULL,
                pending BOOLEAN NOT NULL,
                PRIMARY KEY(id)
            )');
        $this->sql('CREATE UNIQUE INDEX place_unique ON pick_up_place (transport_type, place_id)');
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}

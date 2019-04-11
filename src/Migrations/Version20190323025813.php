<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190323025813 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, stock_no VARCHAR(15) NOT NULL, vin VARCHAR(20) NOT NULL, new_used VARCHAR(1) NOT NULL, veh_year VARCHAR(4) NOT NULL, veh_make VARCHAR(25) NOT NULL, veh_class VARCHAR(25) DEFAULT NULL, veh_model VARCHAR(50) DEFAULT NULL, veh_trim VARCHAR(100) DEFAULT NULL, trans_type VARCHAR(100) DEFAULT NULL, wheelbase VARCHAR(10) DEFAULT NULL, ext_color VARCHAR(100) DEFAULT NULL, int_color VARCHAR(100) DEFAULT NULL, miles INT NOT NULL, msrp_price INT NOT NULL, list_price INT NOT NULL, days INT NOT NULL, cpo_flag INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vehicle');
    }
}

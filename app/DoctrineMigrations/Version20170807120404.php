<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170807120404 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE equipment_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE equipment_status (id INT NOT NULL, user_id INT DEFAULT NULL, equipment_id INT DEFAULT NULL, source_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, reported_status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, include_user_geolocation BOOLEAN NOT NULL, user_geolocation_lat DOUBLE PRECISION NOT NULL, user_geolocation_lon DOUBLE PRECISION NOT NULL, user_gps_accuracy DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DC8E1A5CA76ED395 ON equipment_status (user_id)');
        $this->addSql('CREATE INDEX IDX_DC8E1A5C517FE9FE ON equipment_status (equipment_id)');
        $this->addSql('CREATE TABLE equipment (id INT NOT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, station_id VARCHAR(255) NOT NULL, station_name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, direction VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE equipment_status ADD CONSTRAINT FK_DC8E1A5CA76ED395 FOREIGN KEY (user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE equipment_status ADD CONSTRAINT FK_DC8E1A5C517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX idx_location_patch_user_id RENAME TO IDX_9477D313A76ED395');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE equipment_status DROP CONSTRAINT FK_DC8E1A5C517FE9FE');
        $this->addSql('DROP SEQUENCE equipment_status_id_seq CASCADE');
        $this->addSql('DROP TABLE equipment_status');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('ALTER INDEX idx_9477d313a76ed395 RENAME TO idx_location_patch_user_id');
    }
}

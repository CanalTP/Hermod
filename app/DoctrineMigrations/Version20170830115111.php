<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170830115111 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE equipment_status DROP CONSTRAINT fk_dc8e1a5c517fe9fe');
        $this->addSql('DROP SEQUENCE location_patch_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE equipment_status_id_seq CASCADE');
        $this->addSql('CREATE TABLE status (id UUID NOT NULL, user_id INT DEFAULT NULL, patched_status VARCHAR(255) NOT NULL, current_status VARCHAR(255) NOT NULL, equipment_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B00651CA76ED395 ON status (user_id)');
        $this->addSql('CREATE INDEX status_equipment_id_idx ON status (equipment_id)');
        $this->addSql('CREATE TABLE location (id UUID NOT NULL, user_id INT DEFAULT NULL, using_reporter_geolocation BOOLEAN NOT NULL, equipment_id UUID NOT NULL, current_lat DOUBLE PRECISION NOT NULL, current_lon DOUBLE PRECISION NOT NULL, patched_lat DOUBLE PRECISION NOT NULL, patched_lon DOUBLE PRECISION NOT NULL, reporter_lat DOUBLE PRECISION NOT NULL, reporter_lon DOUBLE PRECISION NOT NULL, reporter_accuracy DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E9E89CBA76ED395 ON location (user_id)');
        $this->addSql('CREATE INDEX location_equipment_id_idx ON location (equipment_id)');
        $this->addSql('CREATE TABLE elevator (id UUID NOT NULL, station_id VARCHAR(255) NOT NULL, station_name VARCHAR(255) NOT NULL, human_location VARCHAR(255) NOT NULL, direction VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, source_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1398EB3377153098 ON elevator (code)');
        $this->addSql('CREATE TABLE stop_point (id UUID NOT NULL, name VARCHAR(255) NOT NULL, route_id VARCHAR(255) NOT NULL, route_name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, source_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3A47181377153098 ON stop_point (code)');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651CA76ED395 FOREIGN KEY (user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBA76ED395 FOREIGN KEY (user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE equipment_status');
        $this->addSql('DROP TABLE location_patch');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE location_patch_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE equipment_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE equipment (id INT NOT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, station_id VARCHAR(255) NOT NULL, station_name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, direction VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2017-09-05 09:27:22.284839\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2017-09-05 09:27:22.284839\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE equipment_status (id INT NOT NULL, user_id INT DEFAULT NULL, equipment_id INT DEFAULT NULL, source_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, reported_status VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2017-09-05 09:27:22.284839\' NOT NULL, include_user_geolocation BOOLEAN NOT NULL, user_geolocation_lat DOUBLE PRECISION NOT NULL, user_geolocation_lon DOUBLE PRECISION NOT NULL, user_gps_accuracy DOUBLE PRECISION NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2017-09-05 09:27:22.284839\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_dc8e1a5c517fe9fe ON equipment_status (equipment_id)');
        $this->addSql('CREATE INDEX idx_dc8e1a5ca76ed395 ON equipment_status (user_id)');
        $this->addSql('CREATE TABLE location_patch (id INT NOT NULL, user_id INT DEFAULT NULL, source_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2017-09-05 09:27:22.284839\' NOT NULL, using_user_geolocation BOOLEAN NOT NULL, stop_point_id VARCHAR(255) NOT NULL, stop_point_name VARCHAR(255) NOT NULL, stop_point_current_lat DOUBLE PRECISION NOT NULL, stop_point_current_lon DOUBLE PRECISION NOT NULL, stop_point_patched_lat DOUBLE PRECISION NOT NULL, stop_point_patched_lon DOUBLE PRECISION NOT NULL, route_id VARCHAR(255) NOT NULL, route_name VARCHAR(255) NOT NULL, user_geolocation_lat DOUBLE PRECISION NOT NULL, user_geolocation_lon DOUBLE PRECISION NOT NULL, user_gps_accuracy DOUBLE PRECISION NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2017-09-05 09:27:22.284839\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_9477d313a76ed395 ON location_patch (user_id)');
        $this->addSql('ALTER TABLE equipment_status ADD CONSTRAINT fk_dc8e1a5ca76ed395 FOREIGN KEY (user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE equipment_status ADD CONSTRAINT fk_dc8e1a5c517fe9fe FOREIGN KEY (equipment_id) REFERENCES equipment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location_patch ADD CONSTRAINT fk_location_patch_user_id FOREIGN KEY (user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE elevator');
        $this->addSql('DROP TABLE stop_point');
    }
}

<?php

namespace Application\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170906084751 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE location_patch (id UUID NOT NULL, user_id INT DEFAULT NULL, using_reporter_geolocation BOOLEAN NOT NULL, equipment_id UUID NOT NULL, current_lat DOUBLE PRECISION NOT NULL, current_lon DOUBLE PRECISION NOT NULL, patched_lat DOUBLE PRECISION NOT NULL, patched_lon DOUBLE PRECISION NOT NULL, reporter_lat DOUBLE PRECISION NOT NULL, reporter_lon DOUBLE PRECISION NOT NULL, reporter_accuracy INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9477D313A76ED395 ON location_patch (user_id)');
        $this->addSql('CREATE INDEX location_patch_equipment_id_idx ON location_patch (equipment_id)');
        $this->addSql('CREATE TABLE status_patch (id UUID NOT NULL, user_id INT DEFAULT NULL, patched_status VARCHAR(255) NOT NULL, current_status VARCHAR(255) NOT NULL, equipment_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1571C7C6A76ED395 ON status_patch (user_id)');
        $this->addSql('CREATE INDEX status_patch_equipment_id_idx ON status_patch (equipment_id)');
        $this->addSql('ALTER TABLE location_patch ADD CONSTRAINT FK_9477D313A76ED395 FOREIGN KEY (user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_patch ADD CONSTRAINT FK_1571C7C6A76ED395 FOREIGN KEY (user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE location');
        $this->addSql('ALTER TABLE elevator ADD name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX elevator_code_source_name_idx ON elevator (code, source_name)');
        $this->addSql('CREATE UNIQUE INDEX stop_point_code_source_name_idx ON stop_point (code, source_name)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE status (id UUID NOT NULL, user_id INT DEFAULT NULL, patched_status VARCHAR(255) NOT NULL, current_status VARCHAR(255) NOT NULL, equipment_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'now()\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'now()\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX status_equipment_id_idx ON status (equipment_id)');
        $this->addSql('CREATE INDEX idx_7b00651ca76ed395 ON status (user_id)');
        $this->addSql('CREATE TABLE location (id UUID NOT NULL, user_id INT DEFAULT NULL, using_reporter_geolocation BOOLEAN NOT NULL, equipment_id UUID NOT NULL, current_lat DOUBLE PRECISION NOT NULL, current_lon DOUBLE PRECISION NOT NULL, patched_lat DOUBLE PRECISION NOT NULL, patched_lon DOUBLE PRECISION NOT NULL, reporter_lat DOUBLE PRECISION NOT NULL, reporter_lon DOUBLE PRECISION NOT NULL, reporter_accuracy DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'now()\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'now()\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_5e9e89cba76ed395 ON location (user_id)');
        $this->addSql('CREATE INDEX location_equipment_id_idx ON location (equipment_id)');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT fk_7b00651ca76ed395 FOREIGN KEY (user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT fk_5e9e89cba76ed395 FOREIGN KEY (user_id) REFERENCES api_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE location_patch');
        $this->addSql('DROP TABLE status_patch');
        $this->addSql('DROP INDEX elevator_code_source_name_idx');
        $this->addSql('ALTER TABLE elevator DROP name');
        $this->addSql('DROP INDEX stop_point_code_source_name_idx');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240904065022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE fleet_fleet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE location_location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE fleet (fleet_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(fleet_id))');
        $this->addSql('CREATE TABLE fleets_vehicles (fleet_id INT NOT NULL, vehicle_plate_number VARCHAR(255) NOT NULL, PRIMARY KEY(fleet_id, vehicle_plate_number))');
        $this->addSql('CREATE INDEX IDX_3690D9794B061DF9 ON fleets_vehicles (fleet_id)');
        $this->addSql('CREATE INDEX IDX_3690D9794517D8F1 ON fleets_vehicles (vehicle_plate_number)');
        $this->addSql('CREATE TABLE location (location_id INT NOT NULL, longitude VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, PRIMARY KEY(location_id))');
        $this->addSql('CREATE TABLE vehicle (vehicle_plate_number VARCHAR(255) NOT NULL, location_id INT DEFAULT NULL, vehicle_type VARCHAR(255) NOT NULL, PRIMARY KEY(vehicle_plate_number))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B80E48664D218E ON vehicle (location_id)');
        $this->addSql('ALTER TABLE fleets_vehicles ADD CONSTRAINT FK_3690D9794B061DF9 FOREIGN KEY (fleet_id) REFERENCES fleet (fleet_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE fleets_vehicles ADD CONSTRAINT FK_3690D9794517D8F1 FOREIGN KEY (vehicle_plate_number) REFERENCES vehicle (vehicle_plate_number) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E48664D218E FOREIGN KEY (location_id) REFERENCES location (location_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE fleet_fleet_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE location_location_id_seq CASCADE');
        $this->addSql('ALTER TABLE fleets_vehicles DROP CONSTRAINT FK_3690D9794B061DF9');
        $this->addSql('ALTER TABLE fleets_vehicles DROP CONSTRAINT FK_3690D9794517D8F1');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E48664D218E');
        $this->addSql('DROP TABLE fleet');
        $this->addSql('DROP TABLE fleets_vehicles');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE vehicle');
    }
}

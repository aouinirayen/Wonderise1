<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226151025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, full_name VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, profile_photo VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL, gender VARCHAR(20) DEFAULT NULL, nationality VARCHAR(100) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, country VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, interests JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', created_at DATETIME NOT NULL, notification_preferences JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', is_blocked TINYINT(1) DEFAULT 0 NOT NULL, date_inscription DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reclamation DROP telephone');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `user`');
        $this->addSql('ALTER TABLE reclamation ADD telephone VARCHAR(20) DEFAULT NULL');
    }
}

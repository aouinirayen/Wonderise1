<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226153150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_CE606404A76ED395 ON reclamation (user_id)');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', ADD password VARCHAR(255) NOT NULL, ADD full_name VARCHAR(255) DEFAULT NULL, ADD username VARCHAR(255) NOT NULL, ADD profile_photo VARCHAR(255) DEFAULT NULL, ADD birth_date DATE DEFAULT NULL, ADD gender VARCHAR(20) DEFAULT NULL, ADD nationality VARCHAR(100) DEFAULT NULL, ADD street VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(10) DEFAULT NULL, ADD city VARCHAR(100) DEFAULT NULL, ADD country VARCHAR(100) DEFAULT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD interests JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD created_at DATETIME NOT NULL, ADD notification_preferences JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD is_blocked TINYINT(1) DEFAULT 0 NOT NULL, ADD date_inscription DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('DROP INDEX IDX_CE606404A76ED395 ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP user_id');
        $this->addSql('ALTER TABLE `user` DROP roles, DROP password, DROP full_name, DROP username, DROP profile_photo, DROP birth_date, DROP gender, DROP nationality, DROP street, DROP postal_code, DROP city, DROP country, DROP phone, DROP interests, DROP created_at, DROP notification_preferences, DROP is_blocked, DROP date_inscription');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228133915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE art (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, INDEX IDX_FC35D654F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, currency VARCHAR(3) DEFAULT NULL, iso_code VARCHAR(2) DEFAULT NULL, calling_code VARCHAR(5) DEFAULT NULL, climate VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monument (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, description VARCHAR(1000) DEFAULT NULL, INDEX IDX_7BB88283F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, is_like TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D8892622F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE selebrity (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, work VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, job VARCHAR(255) NOT NULL, date_of_birth DATE NOT NULL, nationality VARCHAR(255) NOT NULL, notable_works LONGTEXT NOT NULL, personal_life LONGTEXT NOT NULL, net_worth DOUBLE PRECISION NOT NULL, INDEX IDX_A44B34E6F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traditional_food (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, img VARCHAR(255) DEFAULT NULL, description VARCHAR(1000) NOT NULL, recipe VARCHAR(2000) NOT NULL, INDEX IDX_1B2F834EF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE art ADD CONSTRAINT FK_FC35D654F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE monument ADD CONSTRAINT FK_7BB88283F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE selebrity ADD CONSTRAINT FK_A44B34E6F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE traditional_food ADD CONSTRAINT FK_1B2F834EF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE art DROP FOREIGN KEY FK_FC35D654F92F3E70');
        $this->addSql('ALTER TABLE monument DROP FOREIGN KEY FK_7BB88283F92F3E70');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622F92F3E70');
        $this->addSql('ALTER TABLE selebrity DROP FOREIGN KEY FK_A44B34E6F92F3E70');
        $this->addSql('ALTER TABLE traditional_food DROP FOREIGN KEY FK_1B2F834EF92F3E70');
        $this->addSql('DROP TABLE art');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE monument');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE selebrity');
        $this->addSql('DROP TABLE traditional_food');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

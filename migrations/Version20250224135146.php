<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224135146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Add dateModification column to commentaire table
        $this->addSql('ALTER TABLE commentaire ADD date_modification DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // Remove dateModification column from commentaire table
        $this->addSql('ALTER TABLE commentaire DROP date_modification');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240317002000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date_creation column to experience table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience ADD COLUMN date_creation DATETIME DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience DROP COLUMN date_creation');
    }
} 
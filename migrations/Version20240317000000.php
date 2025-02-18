<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240317000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date_creation column to experience table';
    }

    public function up(Schema $schema): void
    {
        // Drop the column if it exists to avoid errors
        $this->addSql('ALTER TABLE experience DROP COLUMN IF EXISTS date_creation');
        
        // Add the column with default value
        $this->addSql('ALTER TABLE experience ADD date_creation DATETIME DEFAULT CURRENT_TIMESTAMP');
        
        // Update existing records
        $this->addSql('UPDATE experience SET date_creation = CURRENT_TIMESTAMP WHERE date_creation IS NULL');
        
        // Make the column NOT NULL after setting values
        $this->addSql('ALTER TABLE experience MODIFY date_creation DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience DROP COLUMN date_creation');
    }
} 
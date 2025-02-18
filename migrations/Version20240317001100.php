<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240317001100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date_creation to experience table with safety checks';
    }

    public function up(Schema $schema): void
    {
        // Check if column exists and drop it
        $this->addSql('
            SET @exist := (
                SELECT COUNT(*)
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = "experience" 
                AND COLUMN_NAME = "date_creation"
            );
        ');
        
        $this->addSql('
            SET @sqlstmt := IF(
                @exist > 0,
                "ALTER TABLE experience DROP COLUMN date_creation",
                "SELECT \'Column does not exist\'"
            );
        ');
        
        $this->addSql('PREPARE stmt FROM @sqlstmt');
        $this->addSql('EXECUTE stmt');
        $this->addSql('DEALLOCATE PREPARE stmt');

        // Add the column fresh
        $this->addSql('ALTER TABLE experience ADD date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('UPDATE experience SET date_creation = NOW() WHERE date_creation IS NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience DROP COLUMN date_creation');
    }
} 
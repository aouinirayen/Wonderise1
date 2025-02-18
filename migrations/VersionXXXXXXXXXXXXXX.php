<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionXXXXXXXXXXXXXX extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add dateCreation field to Experience entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience ADD date_creation DATETIME NOT NULL');
        // Set existing rows to current timestamp
        $this->addSql('UPDATE experience SET date_creation = NOW() WHERE date_creation IS NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience DROP date_creation');
    }
} 
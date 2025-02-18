<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240317001000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date_creation to experience table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience ADD date_creation DATETIME NOT NULL');
        $this->addSql('UPDATE experience SET date_creation = NOW()');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE experience DROP date_creation');
    }
} 
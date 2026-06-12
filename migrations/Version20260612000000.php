<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260612000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add html_color to project table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project ADD html_color VARCHAR(7) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project DROP html_color');
    }
}

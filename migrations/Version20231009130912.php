<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231009130912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projects_users (project_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8102F1D7166D1F9C (project_id), INDEX IDX_8102F1D7A76ED395 (user_id), PRIMARY KEY(project_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasks_users (task_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AD0F9E4E8DB60186 (task_id), INDEX IDX_AD0F9E4EA76ED395 (user_id), PRIMARY KEY(task_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projects_users ADD CONSTRAINT FK_8102F1D7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_users ADD CONSTRAINT FK_8102F1D7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tasks_users ADD CONSTRAINT FK_AD0F9E4E8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tasks_users ADD CONSTRAINT FK_AD0F9E4EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects_users DROP FOREIGN KEY FK_8102F1D7166D1F9C');
        $this->addSql('ALTER TABLE projects_users DROP FOREIGN KEY FK_8102F1D7A76ED395');
        $this->addSql('ALTER TABLE tasks_users DROP FOREIGN KEY FK_AD0F9E4E8DB60186');
        $this->addSql('ALTER TABLE tasks_users DROP FOREIGN KEY FK_AD0F9E4EA76ED395');
        $this->addSql('DROP TABLE projects_users');
        $this->addSql('DROP TABLE tasks_users');
    }
}

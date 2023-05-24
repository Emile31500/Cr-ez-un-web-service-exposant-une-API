<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524071852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_8D93D64919EB6921 ON client');
        $this->addSql('ALTER TABLE client CHANGE user_id project_id INT NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_C7440455166D1F9C ON client (project_id)');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74 ON client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455166D1F9C');
        $this->addSql('DROP INDEX IDX_C7440455166D1F9C ON client');
        $this->addSql('ALTER TABLE client CHANGE project_id user_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_8D93D64919EB6921 ON client (user_id)');
        $this->addSql('DROP INDEX uniq_c7440455e7927c74 ON client');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON client (email)');
    }
}

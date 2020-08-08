<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200807161253 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence DROP FOREIGN KEY FK_94D4687FB3E9C81');
        $this->addSql('DROP INDEX IDX_94D4687FB3E9C81 ON competence');
        $this->addSql('ALTER TABLE competence DROP niveau_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence ADD niveau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE competence ADD CONSTRAINT FK_94D4687FB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau_evaluation (id)');
        $this->addSql('CREATE INDEX IDX_94D4687FB3E9C81 ON competence (niveau_id)');
    }
}

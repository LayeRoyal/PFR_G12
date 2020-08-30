<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200827113046 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA8FB20E9C');
        $this->addSql('DROP INDEX IDX_659DF2AA8FB20E9C ON chat');
        $this->addSql('ALTER TABLE chat CHANGE fil_de_discution_id promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAD0C07AFF ON chat (promo_id)');
        $this->addSql('ALTER TABLE fil_de_discussion DROP FOREIGN KEY FK_399E12C5D0C07AFF');
        $this->addSql('DROP INDEX UNIQ_399E12C5D0C07AFF ON fil_de_discussion');
        $this->addSql('ALTER TABLE fil_de_discussion DROP promo_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAD0C07AFF');
        $this->addSql('DROP INDEX IDX_659DF2AAD0C07AFF ON chat');
        $this->addSql('ALTER TABLE chat CHANGE promo_id fil_de_discution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA8FB20E9C FOREIGN KEY (fil_de_discution_id) REFERENCES fil_de_discussion (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA8FB20E9C ON chat (fil_de_discution_id)');
        $this->addSql('ALTER TABLE fil_de_discussion ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fil_de_discussion ADD CONSTRAINT FK_399E12C5D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_399E12C5D0C07AFF ON fil_de_discussion (promo_id)');
    }
}

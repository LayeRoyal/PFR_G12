<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200823010845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_9E78008CC5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendus (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_rendu (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, livrable_partiel_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, delai DATE NOT NULL, date_de_rendu DATETIME NOT NULL, INDEX IDX_9033AB0FC5697D6D (apprenant_id), INDEX IDX_9033AB0F519178C4 (livrable_partiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livrable ADD CONSTRAINT FK_9E78008CC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE livrable_rendu ADD CONSTRAINT FK_9033AB0FC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE livrable_rendu ADD CONSTRAINT FK_9033AB0F519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiel (id)');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAD0C07AFF');
        $this->addSql('DROP INDEX IDX_659DF2AAD0C07AFF ON chat');
        $this->addSql('ALTER TABLE chat ADD date DATE NOT NULL, CHANGE promo_id fil_de_discution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA8FB20E9C FOREIGN KEY (fil_de_discution_id) REFERENCES fil_de_discussion (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA8FB20E9C ON chat (fil_de_discution_id)');
        $this->addSql('ALTER TABLE commentaire ADD livrable_rendu_id INT DEFAULT NULL, ADD pj VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC9F3E86A9 FOREIGN KEY (livrable_rendu_id) REFERENCES livrable_rendu (id)');
        $this->addSql('CREATE INDEX IDX_67F068BC9F3E86A9 ON commentaire (livrable_rendu_id)');
        $this->addSql('ALTER TABLE fil_de_discussion ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fil_de_discussion ADD CONSTRAINT FK_399E12C5D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_399E12C5D0C07AFF ON fil_de_discussion (promo_id)');
        $this->addSql('ALTER TABLE livrable_partiel DROP nbre_rendu, DROP nbre_corriger');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC9F3E86A9');
        $this->addSql('DROP TABLE livrable');
        $this->addSql('DROP TABLE livrable_attendus');
        $this->addSql('DROP TABLE livrable_rendu');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA8FB20E9C');
        $this->addSql('DROP INDEX IDX_659DF2AA8FB20E9C ON chat');
        $this->addSql('ALTER TABLE chat DROP date, CHANGE fil_de_discution_id promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAD0C07AFF ON chat (promo_id)');
        $this->addSql('DROP INDEX IDX_67F068BC9F3E86A9 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP livrable_rendu_id, DROP pj');
        $this->addSql('ALTER TABLE fil_de_discussion DROP FOREIGN KEY FK_399E12C5D0C07AFF');
        $this->addSql('DROP INDEX UNIQ_399E12C5D0C07AFF ON fil_de_discussion');
        $this->addSql('ALTER TABLE fil_de_discussion DROP promo_id');
        $this->addSql('ALTER TABLE livrable_partiel ADD nbre_rendu INT NOT NULL, ADD nbre_corriger INT NOT NULL');
    }
}

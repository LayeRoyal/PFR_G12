<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822165606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_livrable_partiel (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, livrable_partiel_id INT DEFAULT NULL, fil_de_discussion_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, delai DATE NOT NULL, INDEX IDX_8572D6ADC5697D6D (apprenant_id), INDEX IDX_8572D6AD519178C4 (livrable_partiel_id), UNIQUE INDEX UNIQ_8572D6AD9E665F32 (fil_de_discussion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, promo_id INT DEFAULT NULL, user_id INT DEFAULT NULL, message LONGTEXT NOT NULL, piece_jointes VARCHAR(255) NOT NULL, INDEX IDX_659DF2AAD0C07AFF (promo_id), INDEX IDX_659DF2AAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, formateur_id INT DEFAULT NULL, description LONGTEXT NOT NULL, create_at DATETIME NOT NULL, INDEX IDX_67F068BC155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fil_de_discussion (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_partiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, delai DATE NOT NULL, description LONGTEXT NOT NULL, nbre_rendu INT NOT NULL, nbre_corriger INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6ADC5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6AD519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiel (id)');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel ADD CONSTRAINT FK_8572D6AD9E665F32 FOREIGN KEY (fil_de_discussion_id) REFERENCES fil_de_discussion (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apprenant_livrable_partiel DROP FOREIGN KEY FK_8572D6AD9E665F32');
        $this->addSql('ALTER TABLE apprenant_livrable_partiel DROP FOREIGN KEY FK_8572D6AD519178C4');
        $this->addSql('DROP TABLE apprenant_livrable_partiel');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE fil_de_discussion');
        $this->addSql('DROP TABLE livrable_partiel');
    }
}

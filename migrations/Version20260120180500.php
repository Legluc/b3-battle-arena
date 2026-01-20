<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260120180500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, ddn DATETIME NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FD71A9C55126AC48 (mail), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rencontre (id INT AUTO_INCREMENT NOT NULL, joueur1_id INT NOT NULL, joueur2_id INT NOT NULL, gagnant_id INT DEFAULT NULL, pdf_link VARCHAR(255) DEFAULT NULL, INDEX IDX_460C35ED92C1E237 (joueur1_id), INDEX IDX_460C35ED80744DD9 (joueur2_id), INDEX IDX_460C35ED2F942B8 (gagnant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat (id INT AUTO_INCREMENT NOT NULL, rencontre_id INT NOT NULL, score_joueur1 INT NOT NULL, score_joueur2 INT NOT NULL, UNIQUE INDEX UNIQ_E7DB5DE26CFC0818 (rencontre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED92C1E237 FOREIGN KEY (joueur1_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED80744DD9 FOREIGN KEY (joueur2_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE rencontre ADD CONSTRAINT FK_460C35ED2F942B8 FOREIGN KEY (gagnant_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE26CFC0818 FOREIGN KEY (rencontre_id) REFERENCES rencontre (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED92C1E237');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED80744DD9');
        $this->addSql('ALTER TABLE rencontre DROP FOREIGN KEY FK_460C35ED2F942B8');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE26CFC0818');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE rencontre');
        $this->addSql('DROP TABLE resultat');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

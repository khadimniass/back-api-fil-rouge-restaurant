<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714150831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DD0C07AFF');
        $this->addSql('CREATE TABLE taille_boisson (id INT AUTO_INCREMENT NOT NULL, menu_boisson_id INT DEFAULT NULL, boisson_id INT DEFAULT NULL, quantite INT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, INDEX IDX_59FAC26852E6345B (menu_boisson_id), INDEX IDX_59FAC268734B8089 (boisson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE taille_boisson ADD CONSTRAINT FK_59FAC26852E6345B FOREIGN KEY (menu_boisson_id) REFERENCES menu_boisson (id)');
        $this->addSql('ALTER TABLE taille_boisson ADD CONSTRAINT FK_59FAC268734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id)');
        $this->addSql('DROP TABLE boisson_burger');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP INDEX IDX_6EEAA67DD0C07AFF ON commande');
        $this->addSql('ALTER TABLE commande DROP promo_id');
        $this->addSql('ALTER TABLE ligne_commande ADD taille_boisson_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B8421F13F FOREIGN KEY (taille_boisson_id) REFERENCES taille_boisson (id)');
        $this->addSql('CREATE INDEX IDX_3170B74B8421F13F ON ligne_commande (taille_boisson_id)');
        $this->addSql('ALTER TABLE menu_boisson DROP FOREIGN KEY FK_34CD5F3734B8089');
        $this->addSql('DROP INDEX IDX_34CD5F3734B8089 ON menu_boisson');
        $this->addSql('ALTER TABLE menu_boisson DROP boisson_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B8421F13F');
        $this->addSql('CREATE TABLE boisson_burger (boisson_id INT NOT NULL, burger_id INT NOT NULL, INDEX IDX_97C9FFD417CE5090 (burger_id), INDEX IDX_97C9FFD4734B8089 (boisson_id), PRIMARY KEY(boisson_id, burger_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, poucentage INT NOT NULL, etat INT DEFAULT 1, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE boisson_burger ADD CONSTRAINT FK_97C9FFD417CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE boisson_burger ADD CONSTRAINT FK_97C9FFD4734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE taille_boisson');
        $this->addSql('ALTER TABLE commande ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6EEAA67DD0C07AFF ON commande (promo_id)');
        $this->addSql('DROP INDEX IDX_3170B74B8421F13F ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande DROP taille_boisson_id');
        $this->addSql('ALTER TABLE menu_boisson ADD boisson_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu_boisson ADD CONSTRAINT FK_34CD5F3734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_34CD5F3734B8089 ON menu_boisson (boisson_id)');
    }
}

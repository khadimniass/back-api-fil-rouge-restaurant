<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220802104835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_boisson ADD nom VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE menu_taille ADD qte_boissondan_menu INT NOT NULL');
        $this->addSql('ALTER TABLE taille_boisson ADD qte_boisson_dispo INT NOT NULL, DROP quantite');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_boisson DROP nom');
        $this->addSql('ALTER TABLE menu_taille DROP qte_boissondan_menu');
        $this->addSql('ALTER TABLE taille_boisson ADD quantite INT DEFAULT NULL, DROP qte_boisson_dispo');
    }
}

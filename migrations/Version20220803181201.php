<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803181201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE frite_burger');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE frite_burger (frite_id INT NOT NULL, burger_id INT NOT NULL, INDEX IDX_89FE95F917CE5090 (burger_id), INDEX IDX_89FE95F9BE00B4D9 (frite_id), PRIMARY KEY(frite_id, burger_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE frite_burger ADD CONSTRAINT FK_89FE95F917CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE frite_burger ADD CONSTRAINT FK_89FE95F9BE00B4D9 FOREIGN KEY (frite_id) REFERENCES frite (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211022094631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE libelle (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_libelle (produit_id INT NOT NULL, libelle_id INT NOT NULL, INDEX IDX_622D8C20F347EFB (produit_id), INDEX IDX_622D8C2025DD318D (libelle_id), PRIMARY KEY(produit_id, libelle_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_libelle ADD CONSTRAINT FK_622D8C20F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_libelle ADD CONSTRAINT FK_622D8C2025DD318D FOREIGN KEY (libelle_id) REFERENCES libelle (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_libelle DROP FOREIGN KEY FK_622D8C2025DD318D');
        $this->addSql('DROP TABLE libelle');
        $this->addSql('DROP TABLE produit_libelle');
    }
}

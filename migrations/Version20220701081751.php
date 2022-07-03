<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220701081751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_membre_id INTEGER DEFAULT NULL, id_produit_id INTEGER DEFAULT NULL, quantite INTEGER NOT NULL, montant DOUBLE PRECISION NOT NULL, etat VARCHAR(255) NOT NULL, date_enregistrement DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DEAAC4B6D ON commande (id_membre_id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DAABEFE2C ON commande (id_produit_id)');
        $this->addSql('CREATE TABLE membre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pseudo VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(20) NOT NULL, prenom VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, civilite VARCHAR(1) NOT NULL, statut INTEGER NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , date_enregistrement DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6B4FB2986CC499D ON membre (pseudo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6B4FB29E7927C74 ON membre (email)');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, couleur VARCHAR(50) NOT NULL, taille VARCHAR(50) NOT NULL, collection VARCHAR(1) NOT NULL, photo VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, stock INTEGER NOT NULL, date_enregistrement DATETIME NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE produit');
    }
}

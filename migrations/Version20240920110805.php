<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903122250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de description à album, remplacement de la colonne admin par roles et password dans user';
    }

    public function up(Schema $schema): void
    {
        // Ajout de la colonne description à la table album
        $this->addSql('ALTER TABLE album ADD description LONGTEXT DEFAULT NULL');

        // Suppression de la colonne admin et ajout des colonnes roles et password à la table user
        $this->addSql('ALTER TABLE `user` DROP admin');
        $this->addSql('ALTER TABLE `user` ADD roles JSON NOT NULL');
        $this->addSql('ALTER TABLE `user` ADD password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // Retrait de la colonne description de la table album
        $this->addSql('ALTER TABLE album DROP description');

        // Annuler les changements sur la table user : suppression des colonnes roles et password, et réajout de la colonne admin
        $this->addSql('ALTER TABLE `user` ADD admin TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE `user` DROP roles');
        $this->addSql('ALTER TABLE `user` DROP password');
    }
}

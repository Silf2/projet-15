<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240922123456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables for Album, Media and User entities';
    }

    public function up(Schema $schema): void
    {
        // this is for sqlite; adapt for your environment if necessary
        $this->addSql('CREATE TABLE album (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        
        $this->addSql('CREATE TABLE media (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            user_id INTEGER, 
            album_id INTEGER, 
            path VARCHAR(255) NOT NULL, 
            title VARCHAR(255) NOT NULL, 
            CONSTRAINT FK_MEDIA_USER FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
            CONSTRAINT FK_MEDIA_ALBUM FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        )');

        $this->addSql('CREATE TABLE "user" (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            name VARCHAR(255), 
            description TEXT DEFAULT NULL, 
            email VARCHAR(180) NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            roles CLOB NOT NULL
        )');
        
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // Drop tables in reverse order to maintain foreign key constraints
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE "user"');
    }
}
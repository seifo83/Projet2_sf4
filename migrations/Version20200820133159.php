<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200820133159 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, released_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE record (id INT AUTO_INCREMENT NOT NULL, artist_id INT NOT NULL, label_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, released_at DATE NOT NULL, INDEX IDX_9B349F91B7970CF8 (artist_id), INDEX IDX_9B349F9133B92F39 (label_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F91B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F9133B92F39 FOREIGN KEY (label_id) REFERENCES label (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F91B7970CF8');
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F9133B92F39');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE record');
    }
}

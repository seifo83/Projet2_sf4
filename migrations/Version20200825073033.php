<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200825073033 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // Etapes pour ajouter une colonne NOT NULL et unique
        // sur une table avec des lignes déja existantes

        //1. Ajouter la colonne en acceptant la valeur NULL (on modifie NOT NULL par DEFAULT NULL)
        $this->addSql('ALTER TABLE user ADD pseudo VARCHAR(30) DEFAULT NULL');

        //2. Définir une valeur à la nouvelle colone pour toutes les lignes
        //      La valeur va se baser sur la clé primaire pour étre unisque
        $this->addSql('UPDATE user SET pseudo = CONCAT("user_", id)');


        //3.  Remettre la colone en NOT NULL 
        $this->addSql('ALTER TABLE user MODIFY pseudo VARCHAR(30) NOT NULL');

        //4.Rendre la colone unique
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986CC499D ON user (pseudo)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D64986CC499D ON user');
        $this->addSql('ALTER TABLE user DROP pseudo');
    }
}

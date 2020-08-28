<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826144305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD is_confirmed TINYINT(1) DEFAULT NULL, ADD token VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE user SET is_confirmed = TRUE, token = SUBSTR(HEX(SHA2(CONCAT(NOW(), RAND(), UUID()), 256)), 1, 50)');
        /**
         * HEX(SHA2(CONCAT(NOW(), RAND(), UUID()), 512));
         *   méthodes de chiffrement mySQL. si on a besoin d'un cryptage bidirectionnel
         *  vous pouvez utiliser celui aes_encryptqui a l'accompagnementaes_decrypt Si vous n'avez besoin que d'un hachage sécurisé, vous pouvez utiliser sha2
         *  L'instruction suivante pourrait vous donner un résultat similaire à openssl_random_pseudo_bytes
         *  SELECT HEX(SHA2(CONCAT(NOW(), RAND(), UUID()), 512)); 
         *  L'instruction ci-dessus prend NOW()et concatène avec RAND()et a UUID(), puis effectue un SHA2()cryptage de 512 bits sur le résultat, puis le convertit enHEX()
         * 
         * Remarque : Dans notre Projet on n'est pas besoin que la méthode génerer  512 chiffres c'est trop long 
         * on a modifier la méthode pour effectuer le cryptage sur 256 bits et on recupére seulement de premier element jusqu'a 50 (de 1 --> 50)
         * 
         */ 

        $this->addSql('ALTER TABLE user MODIFY is_confirmed TINYINT(1) NOT NULL, MODIFY token VARCHAR(255) NOT NULL');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP is_confirmed, DROP token');
    }
}

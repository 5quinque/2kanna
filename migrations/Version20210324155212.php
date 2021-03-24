<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324155212 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banned (id INT AUTO_INCREMENT NOT NULL, ip_address VARCHAR(64) NOT NULL, ban_time DATETIME NOT NULL, unban_time DATETIME NOT NULL, reason VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE board (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(127) NOT NULL, UNIQUE INDEX UNIQ_58562B475E237E06 (name), INDEX IDX_58562B477E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, board_id INT NOT NULL, parent_id INT DEFAULT NULL, message LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, latestpost DATETIME DEFAULT NULL, image_name VARCHAR(127) DEFAULT NULL, ip_address VARCHAR(64) NOT NULL, image_mime_type VARCHAR(127) DEFAULT NULL, slug VARCHAR(255) NOT NULL, sticky TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 (slug), INDEX IDX_5A8A6C8DE7EC5785 (board_id), INDEX IDX_5A8A6C8D727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, placement INT NOT NULL, type VARCHAR(255) NOT NULL, value_bool TINYINT(1) DEFAULT NULL, section VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting_choice (id INT AUTO_INCREMENT NOT NULL, setting_id INT NOT NULL, value VARCHAR(255) NOT NULL, sc_key VARCHAR(255) NOT NULL, INDEX IDX_FB57A3AFEE35BD72 (setting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE twokuser (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_DF605C20F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE word_filter (id INT AUTO_INCREMENT NOT NULL, bad_word VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B477E3C61F9 FOREIGN KEY (owner_id) REFERENCES twokuser (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D727ACA70 FOREIGN KEY (parent_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE setting_choice ADD CONSTRAINT FK_FB57A3AFEE35BD72 FOREIGN KEY (setting_id) REFERENCES setting (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DE7EC5785');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D727ACA70');
        $this->addSql('ALTER TABLE setting_choice DROP FOREIGN KEY FK_FB57A3AFEE35BD72');
        $this->addSql('ALTER TABLE board DROP FOREIGN KEY FK_58562B477E3C61F9');
        $this->addSql('DROP TABLE banned');
        $this->addSql('DROP TABLE board');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE setting_choice');
        $this->addSql('DROP TABLE twokuser');
        $this->addSql('DROP TABLE word_filter');
    }
}

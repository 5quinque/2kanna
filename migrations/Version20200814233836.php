<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200814233836 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE admin_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE banned_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE board_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE setting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE setting_choice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE word_filter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76F85E0677 ON admin (username)');
        $this->addSql('CREATE TABLE banned (id INT NOT NULL, ip_address VARCHAR(64) NOT NULL, ban_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, unban_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, reason VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE board (id INT NOT NULL, name VARCHAR(127) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_58562B475E237E06 ON board (name)');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, board_id INT NOT NULL, parent_id INT DEFAULT NULL, message TEXT DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, latestpost TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, image_name VARCHAR(127) DEFAULT NULL, ip_address VARCHAR(64) NOT NULL, image_mime_type VARCHAR(127) DEFAULT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 ON post (slug)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DE7EC5785 ON post (board_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D727ACA70 ON post (parent_id)');
        $this->addSql('CREATE TABLE setting (id INT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, placement INT NOT NULL, type VARCHAR(255) NOT NULL, value_bool BOOLEAN DEFAULT NULL, section VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE setting_choice (id INT NOT NULL, setting_id INT NOT NULL, value VARCHAR(255) NOT NULL, key VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FB57A3AFEE35BD72 ON setting_choice (setting_id)');
        $this->addSql('CREATE TABLE word_filter (id INT NOT NULL, bad_word VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DE7EC5785 FOREIGN KEY (board_id) REFERENCES board (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D727ACA70 FOREIGN KEY (parent_id) REFERENCES post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE setting_choice ADD CONSTRAINT FK_FB57A3AFEE35BD72 FOREIGN KEY (setting_id) REFERENCES setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DE7EC5785');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D727ACA70');
        $this->addSql('ALTER TABLE setting_choice DROP CONSTRAINT FK_FB57A3AFEE35BD72');
        $this->addSql('DROP SEQUENCE admin_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE banned_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE board_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE setting_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE setting_choice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE word_filter_id_seq CASCADE');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE banned');
        $this->addSql('DROP TABLE board');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE setting_choice');
        $this->addSql('DROP TABLE word_filter');
    }
}

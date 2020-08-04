<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200804222834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE setting_choice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE setting_choice (id INT NOT NULL, setting_id INT NOT NULL, value VARCHAR(255) NOT NULL, key VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FB57A3AFEE35BD72 ON setting_choice (setting_id)');
        $this->addSql('ALTER TABLE setting_choice ADD CONSTRAINT FK_FB57A3AFEE35BD72 FOREIGN KEY (setting_id) REFERENCES setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE setting_choice_id_seq CASCADE');
        $this->addSql('DROP TABLE setting_choice');
    }
}

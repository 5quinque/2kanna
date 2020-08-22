<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822194249 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE board ADD CONSTRAINT FK_58562B477E3C61F9 FOREIGN KEY (owner_id) REFERENCES twokuser (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_58562B477E3C61F9 ON board (owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board DROP CONSTRAINT FK_58562B477E3C61F9');
        $this->addSql('DROP INDEX IDX_58562B477E3C61F9');
        $this->addSql('ALTER TABLE board DROP owner_id');
    }
}

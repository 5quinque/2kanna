<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191012183920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D39C1776A');
        $this->addSql('DROP INDEX IDX_5A8A6C8D39C1776A ON post');
        $this->addSql('ALTER TABLE post ADD parent_id INT DEFAULT NULL, DROP parent_post_id, CHANGE title title VARCHAR(127) DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D727ACA70 FOREIGN KEY (parent_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D727ACA70 ON post (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D727ACA70');
        $this->addSql('DROP INDEX IDX_5A8A6C8D727ACA70 ON post');
        $this->addSql('ALTER TABLE post ADD parent_post_id INT DEFAULT NULL, DROP parent_id, CHANGE title title VARCHAR(127) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D39C1776A FOREIGN KEY (parent_post_id) REFERENCES post (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D39C1776A ON post (parent_post_id)');
    }
}

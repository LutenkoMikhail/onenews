<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201208124635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news (id INT NOT NULL, name VARCHAR(255) NOT NULL, short_description TEXT NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, active BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE news_tags (news_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(news_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_BA6162ADB5A459A0 ON news_tags (news_id)');
        $this->addSql('CREATE INDEX IDX_BA6162ADBAD26311 ON news_tags (tag_id)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B7835E237E06 ON tag (name)');
        $this->addSql('ALTER TABLE news_tags ADD CONSTRAINT FK_BA6162ADB5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE news_tags ADD CONSTRAINT FK_BA6162ADBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE news_tags DROP CONSTRAINT FK_BA6162ADB5A459A0');
        $this->addSql('ALTER TABLE news_tags DROP CONSTRAINT FK_BA6162ADBAD26311');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE news_tags');
        $this->addSql('DROP TABLE tag');
    }
}

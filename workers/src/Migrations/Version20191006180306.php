<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191006180306 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_B6F7494E9E42BB0E');
        $this->addSql('DROP INDEX IDX_B6F7494EF675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__question AS SELECT id, author_id, work_post_id, content, published FROM question');
        $this->addSql('DROP TABLE question');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, work_post_id INTEGER NOT NULL, content CLOB NOT NULL COLLATE BINARY, published DATETIME NOT NULL, CONSTRAINT FK_B6F7494EF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B6F7494E9E42BB0E FOREIGN KEY (work_post_id) REFERENCES work_post (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO question (id, author_id, work_post_id, content, published) SELECT id, author_id, work_post_id, content, published FROM __temp__question');
        $this->addSql('DROP TABLE __temp__question');
        $this->addSql('CREATE INDEX IDX_B6F7494E9E42BB0E ON question (work_post_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EF675F31B ON question (author_id)');
        $this->addSql('ALTER TABLE user ADD COLUMN roles CLOB NOT NULL DEFAULT ' . User::ROLE_QUESTIONER);
        $this->addSql('DROP INDEX IDX_A349DDB5F675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__work_post AS SELECT id, author_id, title, published, content, cv, slug FROM work_post');
        $this->addSql('DROP TABLE work_post');
        $this->addSql('CREATE TABLE work_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, published DATETIME NOT NULL, content CLOB NOT NULL COLLATE BINARY, cv VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_A349DDB5F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO work_post (id, author_id, title, published, content, cv, slug) SELECT id, author_id, title, published, content, cv, slug FROM __temp__work_post');
        $this->addSql('DROP TABLE __temp__work_post');
        $this->addSql('CREATE INDEX IDX_A349DDB5F675F31B ON work_post (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_B6F7494EF675F31B');
        $this->addSql('DROP INDEX IDX_B6F7494E9E42BB0E');
        $this->addSql('CREATE TEMPORARY TABLE __temp__question AS SELECT id, author_id, work_post_id, content, published FROM question');
        $this->addSql('DROP TABLE question');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, work_post_id INTEGER NOT NULL, content CLOB NOT NULL, published DATETIME NOT NULL)');
        $this->addSql('INSERT INTO question (id, author_id, work_post_id, content, published) SELECT id, author_id, work_post_id, content, published FROM __temp__question');
        $this->addSql('DROP TABLE __temp__question');
        $this->addSql('CREATE INDEX IDX_B6F7494EF675F31B ON question (author_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E9E42BB0E ON question (work_post_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, password, name, email FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, username, password, name, email) SELECT id, username, password, name, email FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('DROP INDEX IDX_A349DDB5F675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__work_post AS SELECT id, author_id, title, published, content, cv, slug FROM work_post');
        $this->addSql('DROP TABLE work_post');
        $this->addSql('CREATE TABLE work_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, published DATETIME NOT NULL, content CLOB NOT NULL, cv VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO work_post (id, author_id, title, published, content, cv, slug) SELECT id, author_id, title, published, content, cv, slug FROM __temp__work_post');
        $this->addSql('DROP TABLE __temp__work_post');
        $this->addSql('CREATE INDEX IDX_A349DDB5F675F31B ON work_post (author_id)');
    }
}

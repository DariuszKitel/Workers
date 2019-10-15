<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191015210455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles CLOB DEFAULT \'ROLE_QUESTIONER\' NOT NULL --(DC2Type:simple_array)
        , pass_change_date INTEGER DEFAULT NULL, enabled BOOLEAN DEFAULT \'0\' NOT NULL, confirmation_token VARCHAR(40) DEFAULT NULL)');
        $this->addSql('CREATE TABLE cv (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, urlcv VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, url VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE question (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, work_post_id INTEGER NOT NULL, content CLOB NOT NULL, published DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_B6F7494EF675F31B ON question (author_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E9E42BB0E ON question (work_post_id)');
        $this->addSql('CREATE TABLE work_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, published DATETIME NOT NULL, content CLOB NOT NULL, slug VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_A349DDB5F675F31B ON work_post (author_id)');
        $this->addSql('CREATE TABLE work_post_cv (work_post_id INTEGER NOT NULL, cv_id INTEGER NOT NULL, PRIMARY KEY(work_post_id, cv_id))');
        $this->addSql('CREATE INDEX IDX_971422CC9E42BB0E ON work_post_cv (work_post_id)');
        $this->addSql('CREATE INDEX IDX_971422CCCFE419E2 ON work_post_cv (cv_id)');
        $this->addSql('CREATE TABLE work_post_image (work_post_id INTEGER NOT NULL, image_id INTEGER NOT NULL, PRIMARY KEY(work_post_id, image_id))');
        $this->addSql('CREATE INDEX IDX_494525639E42BB0E ON work_post_image (work_post_id)');
        $this->addSql('CREATE INDEX IDX_494525633DA5256D ON work_post_image (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE work_post');
        $this->addSql('DROP TABLE work_post_cv');
        $this->addSql('DROP TABLE work_post_image');
    }
}

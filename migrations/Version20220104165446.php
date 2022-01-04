<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220104165446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('DROP INDEX UNIQ_4FBF094FA76ED395 ON company');
        $this->addSql('ALTER TABLE company ADD updated_by_id INT DEFAULT NULL, ADD deleted_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL, CHANGE user_id created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FC76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FB03A8386 ON company (created_by_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094F896DBBDE ON company (updated_by_id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FC76F1F52 ON company (deleted_by_id)');
        $this->addSql('ALTER TABLE cron_report ADD error LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD deleted_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B03A8386 ON user (created_by_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649896DBBDE ON user (updated_by_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649C76F1F52 ON user (deleted_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FB03A8386');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F896DBBDE');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FC76F1F52');
        $this->addSql('DROP INDEX IDX_4FBF094FB03A8386 ON company');
        $this->addSql('DROP INDEX IDX_4FBF094F896DBBDE ON company');
        $this->addSql('DROP INDEX IDX_4FBF094FC76F1F52 ON company');
        $this->addSql('ALTER TABLE company ADD user_id INT DEFAULT NULL, DROP created_by_id, DROP updated_by_id, DROP deleted_by_id, DROP created_at, DROP updated_at, DROP deleted_at');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094FA76ED395 ON company (user_id)');
        $this->addSql('ALTER TABLE cron_report DROP error');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B03A8386');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649896DBBDE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C76F1F52');
        $this->addSql('DROP INDEX IDX_8D93D649B03A8386 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649896DBBDE ON user');
        $this->addSql('DROP INDEX IDX_8D93D649C76F1F52 ON user');
        $this->addSql('ALTER TABLE user DROP created_by_id, DROP updated_by_id, DROP deleted_by_id, DROP created_at, DROP updated_at, DROP deleted_at');
    }
}

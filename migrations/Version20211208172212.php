<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208172212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('Alter TABLE beast ADD COLUMN documents tsvector');
        $this->addSql("UPDATE beast set documents = to_tsvector(name || ' ' || description ) where id > 0");
        $this->addSql('Alter TABLE spell ADD COLUMN documents tsvector');
        $this->addSql("UPDATE spell set documents = to_tsvector(name || ' ' || description || ' ' || short_description || ' ' || effect) where id > 0");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beast drop documents');
    }
}

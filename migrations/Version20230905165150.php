<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230905165150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add comment table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE comment (id UUID NOT NULL, author_id UUID DEFAULT NULL, parent_id UUID DEFAULT NULL, body TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C221FC741 ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526C727ACA70 ON comment (parent_id)');
        $this->addSql('COMMENT ON COLUMN comment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.parent_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C221FC741 FOREIGN KEY (author_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C221FC741');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C727ACA70');
        $this->addSql('DROP TABLE comment');
    }
}

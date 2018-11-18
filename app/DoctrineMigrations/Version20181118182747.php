<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181118182747 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, subscriber_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, value VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5BF545587808B1AD (subscriber_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE utf8_lithuanian_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `subscriber` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, state VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE utf8_lithuanian_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF545587808B1AD FOREIGN KEY (subscriber_id) REFERENCES `subscriber` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE field DROP FOREIGN KEY FK_5BF545587808B1AD');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE `subscriber`');
    }
}

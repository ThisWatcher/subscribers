<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181117121958 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE field (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE utf8_lithuanian_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscriber_field (subscriber_id INT NOT NULL, field_id INT NOT NULL, INDEX IDX_6055C7BF7808B1AD (subscriber_id), INDEX IDX_6055C7BF443707B0 (field_id), PRIMARY KEY(subscriber_id, field_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscriber_field ADD CONSTRAINT FK_6055C7BF7808B1AD FOREIGN KEY (subscriber_id) REFERENCES `subscriber` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscriber_field ADD CONSTRAINT FK_6055C7BF443707B0 FOREIGN KEY (field_id) REFERENCES field (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subscriber_field DROP FOREIGN KEY FK_6055C7BF443707B0');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE subscriber_field');
    }
}

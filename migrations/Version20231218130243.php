<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218130243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_subscribers DROP is_active');
        $this->addSql('ALTER TABLE newsletter_subscribers MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON newsletter_subscribers');
        $this->addSql('ALTER TABLE newsletter_subscribers DROP id');
        $this->addSql('ALTER TABLE newsletter_subscribers ADD PRIMARY KEY (adress)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE newsletter_subscribers ADD is_active TINYINT(1) NOT NULL');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE newsletter_subscribers ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}

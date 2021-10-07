<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211007155549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_optad (id INT AUTO_INCREMENT NOT NULL, setting_id INT NOT NULL, urls VARCHAR(255) NOT NULL, tags VARCHAR(255) NOT NULL, date DATE NOT NULL, estimated_revenue INT NOT NULL, ad_impression INT NOT NULL, ad_ecpm INT NOT NULL, clicks INT NOT NULL, ad_ctr DOUBLE PRECISION NOT NULL, INDEX IDX_56D05FBEE35BD72 (setting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_optad ADD CONSTRAINT FK_56D05FBEE35BD72 FOREIGN KEY (setting_id) REFERENCES app_setting (id)');
        $this->addSql('DROP TABLE app_header');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_header (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8B71C5415E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE app_optad');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250308162646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL,
        description LONGTEXT NOT NULL, am_opening_time LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\',
        pm_opening_time LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', max_guest INT NOT NULL,
        created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE restaurant');
    }
}

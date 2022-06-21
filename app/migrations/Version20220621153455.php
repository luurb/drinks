<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621153455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drink (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, preparation LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drink_product (drink_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_F1EE509F36AA4BB4 (drink_id), INDEX IDX_F1EE509F4584665A (product_id), PRIMARY KEY(drink_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drink_category (drink_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CEB9EC1536AA4BB4 (drink_id), INDEX IDX_CEB9EC1512469DE2 (category_id), PRIMARY KEY(drink_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE drink_product ADD CONSTRAINT FK_F1EE509F36AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drink_product ADD CONSTRAINT FK_F1EE509F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drink_category ADD CONSTRAINT FK_CEB9EC1536AA4BB4 FOREIGN KEY (drink_id) REFERENCES drink (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drink_category ADD CONSTRAINT FK_CEB9EC1512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE drink_category DROP FOREIGN KEY FK_CEB9EC1512469DE2');
        $this->addSql('ALTER TABLE drink_product DROP FOREIGN KEY FK_F1EE509F36AA4BB4');
        $this->addSql('ALTER TABLE drink_category DROP FOREIGN KEY FK_CEB9EC1536AA4BB4');
        $this->addSql('ALTER TABLE drink_product DROP FOREIGN KEY FK_F1EE509F4584665A');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE drink');
        $this->addSql('DROP TABLE drink_product');
        $this->addSql('DROP TABLE drink_category');
        $this->addSql('DROP TABLE product');
    }
}

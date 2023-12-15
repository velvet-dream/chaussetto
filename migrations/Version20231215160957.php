<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215160957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_image (product_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_64617F034584665A (product_id), INDEX IDX_64617F033DA5256D (image_id), PRIMARY KEY(product_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CDFC73564584665A (product_id), INDEX IDX_CDFC735612469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F033DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_line ADD cart_id INT NOT NULL, ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart_line ADD CONSTRAINT FK_3EF1B4CF1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE cart_line ADD CONSTRAINT FK_3EF1B4CF4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_3EF1B4CF1AD5CDBF ON cart_line (cart_id)');
        $this->addSql('CREATE INDEX IDX_3EF1B4CF4584665A ON cart_line (product_id)');
        $this->addSql('ALTER TABLE category ADD parent_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1796A8F92 FOREIGN KEY (parent_category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1796A8F92 ON category (parent_category_id)');
        $this->addSql('ALTER TABLE customer ADD cart_id INT DEFAULT NULL, ADD adress_id INT NOT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E091AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E098486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E091AD5CDBF ON customer (cart_id)');
        $this->addSql('CREATE INDEX IDX_81398E098486F9AC ON customer (adress_id)');
        $this->addSql('ALTER TABLE `order` ADD order_state_id INT NOT NULL, ADD customer_id INT NOT NULL, ADD billing_adress_id INT NOT NULL, ADD delivery_adress_id INT NOT NULL, ADD payment_method_id INT NOT NULL, ADD carrier_id INT NOT NULL, ADD cart_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398E420DE70 FOREIGN KEY (order_state_id) REFERENCES order_state (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939830959BF2 FOREIGN KEY (billing_adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398C0E3B53E FOREIGN KEY (delivery_adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993985AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939821DFC797 FOREIGN KEY (carrier_id) REFERENCES carrier (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993981AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_F5299398E420DE70 ON `order` (order_state_id)');
        $this->addSql('CREATE INDEX IDX_F52993989395C3F3 ON `order` (customer_id)');
        $this->addSql('CREATE INDEX IDX_F529939830959BF2 ON `order` (billing_adress_id)');
        $this->addSql('CREATE INDEX IDX_F5299398C0E3B53E ON `order` (delivery_adress_id)');
        $this->addSql('CREATE INDEX IDX_F52993985AA1164F ON `order` (payment_method_id)');
        $this->addSql('CREATE INDEX IDX_F529939821DFC797 ON `order` (carrier_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993981AD5CDBF ON `order` (cart_id)');
        $this->addSql('ALTER TABLE order_line ADD corresponding_order_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1403D6BCE FOREIGN KEY (corresponding_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_9CE58EE1403D6BCE ON order_line (corresponding_order_id)');
        $this->addSql('ALTER TABLE product ADD promotion_id INT DEFAULT NULL, ADD tax_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD139DF194 ON product (promotion_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADB2A824D8 ON product (tax_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F033DA5256D');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('ALTER TABLE cart_line DROP FOREIGN KEY FK_3EF1B4CF1AD5CDBF');
        $this->addSql('ALTER TABLE cart_line DROP FOREIGN KEY FK_3EF1B4CF4584665A');
        $this->addSql('DROP INDEX IDX_3EF1B4CF1AD5CDBF ON cart_line');
        $this->addSql('DROP INDEX IDX_3EF1B4CF4584665A ON cart_line');
        $this->addSql('ALTER TABLE cart_line DROP cart_id, DROP product_id');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1796A8F92');
        $this->addSql('DROP INDEX IDX_64C19C1796A8F92 ON category');
        $this->addSql('ALTER TABLE category DROP parent_category_id');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E091AD5CDBF');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E098486F9AC');
        $this->addSql('DROP INDEX UNIQ_81398E091AD5CDBF ON customer');
        $this->addSql('DROP INDEX IDX_81398E098486F9AC ON customer');
        $this->addSql('ALTER TABLE customer DROP cart_id, DROP adress_id');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398E420DE70');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939830959BF2');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398C0E3B53E');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993985AA1164F');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939821DFC797');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993981AD5CDBF');
        $this->addSql('DROP INDEX IDX_F5299398E420DE70 ON `order`');
        $this->addSql('DROP INDEX IDX_F52993989395C3F3 ON `order`');
        $this->addSql('DROP INDEX IDX_F529939830959BF2 ON `order`');
        $this->addSql('DROP INDEX IDX_F5299398C0E3B53E ON `order`');
        $this->addSql('DROP INDEX IDX_F52993985AA1164F ON `order`');
        $this->addSql('DROP INDEX IDX_F529939821DFC797 ON `order`');
        $this->addSql('DROP INDEX UNIQ_F52993981AD5CDBF ON `order`');
        $this->addSql('ALTER TABLE `order` DROP order_state_id, DROP customer_id, DROP billing_adress_id, DROP delivery_adress_id, DROP payment_method_id, DROP carrier_id, DROP cart_id');
        $this->addSql('ALTER TABLE order_line DROP FOREIGN KEY FK_9CE58EE1403D6BCE');
        $this->addSql('DROP INDEX IDX_9CE58EE1403D6BCE ON order_line');
        $this->addSql('ALTER TABLE order_line DROP corresponding_order_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD139DF194');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB2A824D8');
        $this->addSql('DROP INDEX IDX_D34A04AD139DF194 ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADB2A824D8 ON product');
        $this->addSql('ALTER TABLE product DROP promotion_id, DROP tax_id');
    }
}

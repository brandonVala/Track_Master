<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713055650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, registered_at DATETIME NOT NULL, email_verified TINYINT(1) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, profile_picture VARCHAR(255) DEFAULT NULL, last_login_at DATETIME DEFAULT NULL, notification_settings JSON DEFAULT NULL, access_permissions JSON DEFAULT NULL, action_history JSON DEFAULT NULL, statistics JSON DEFAULT NULL, integrations JSON DEFAULT NULL, area_of_responsibility VARCHAR(255) DEFAULT NULL, is_admin TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geofence (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, admin_id INT NOT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_B4EB6643A76ED395 (user_id), INDEX IDX_B4EB6643642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, admin_id INT DEFAULT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, INDEX IDX_5E9E89CBA76ED395 (user_id), INDEX IDX_5E9E89CB642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, admin_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), INDEX IDX_BF5476CA642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, admin_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, registered_at DATETIME NOT NULL, email_verified TINYINT(1) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE geofence ADD CONSTRAINT FK_B4EB6643A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE geofence ADD CONSTRAINT FK_B4EB6643642B8210 FOREIGN KEY (admin_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB642B8210 FOREIGN KEY (admin_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA642B8210 FOREIGN KEY (admin_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649642B8210 FOREIGN KEY (admin_id) REFERENCES `admin` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE geofence DROP FOREIGN KEY FK_B4EB6643A76ED395');
        $this->addSql('ALTER TABLE geofence DROP FOREIGN KEY FK_B4EB6643642B8210');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBA76ED395');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB642B8210');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA642B8210');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649642B8210');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE geofence');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

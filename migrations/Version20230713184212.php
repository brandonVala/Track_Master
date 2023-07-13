<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713184212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE geofence ADD admin_id INT NOT NULL');
        $this->addSql('ALTER TABLE geofence ADD CONSTRAINT FK_B4EB6643642B8210 FOREIGN KEY (admin_id) REFERENCES `admin` (id)');
        $this->addSql('CREATE INDEX IDX_B4EB6643642B8210 ON geofence (admin_id)');
        $this->addSql('ALTER TABLE location ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB642B8210 FOREIGN KEY (admin_id) REFERENCES `admin` (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB642B8210 ON location (admin_id)');
        $this->addSql('ALTER TABLE notification ADD admin_id INT DEFAULT NULL, ADD geofence_id INT DEFAULT NULL, ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA642B8210 FOREIGN KEY (admin_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA10E201CC FOREIGN KEY (geofence_id) REFERENCES geofence (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA642B8210 ON notification (admin_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA10E201CC ON notification (geofence_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA64D218E ON notification (location_id)');
        $this->addSql('ALTER TABLE user ADD admin_id INT NOT NULL, DROP is_admin, DROP roles');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649642B8210 FOREIGN KEY (admin_id) REFERENCES `admin` (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649642B8210 ON user (admin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA642B8210');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA10E201CC');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA64D218E');
        $this->addSql('DROP INDEX IDX_BF5476CA642B8210 ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CA10E201CC ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CA64D218E ON notification');
        $this->addSql('ALTER TABLE notification DROP admin_id, DROP geofence_id, DROP location_id');
        $this->addSql('ALTER TABLE geofence DROP FOREIGN KEY FK_B4EB6643642B8210');
        $this->addSql('DROP INDEX IDX_B4EB6643642B8210 ON geofence');
        $this->addSql('ALTER TABLE geofence DROP admin_id');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB642B8210');
        $this->addSql('DROP INDEX IDX_5E9E89CB642B8210 ON location');
        $this->addSql('ALTER TABLE location DROP admin_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649642B8210');
        $this->addSql('DROP INDEX IDX_8D93D649642B8210 ON user');
        $this->addSql('ALTER TABLE user ADD is_admin TINYINT(1) NOT NULL, ADD roles JSON NOT NULL, DROP admin_id');
    }
}

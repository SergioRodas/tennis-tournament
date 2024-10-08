<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008192130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matchups (id INT AUTO_INCREMENT NOT NULL, player1_id INT DEFAULT NULL, player2_id INT DEFAULT NULL, winner_id INT DEFAULT NULL, tournament_id INT DEFAULT NULL, INDEX IDX_7B7433FC0990423 (player1_id), INDEX IDX_7B7433FD22CABCD (player2_id), INDEX IDX_7B7433F5DFCD4B8 (winner_id), INDEX IDX_7B7433F33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE players (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, skill_level INT NOT NULL, gender VARCHAR(1) NOT NULL, strength INT DEFAULT NULL, speed INT DEFAULT NULL, reaction_time INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tournaments (id INT AUTO_INCREMENT NOT NULL, gender VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, finished_at DATETIME DEFAULT NULL, winner_id INT DEFAULT NULL, INDEX IDX_E4BCFAC35DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE matchups ADD CONSTRAINT FK_7B7433FC0990423 FOREIGN KEY (player1_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE matchups ADD CONSTRAINT FK_7B7433FD22CABCD FOREIGN KEY (player2_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE matchups ADD CONSTRAINT FK_7B7433F5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES players (id)');
        $this->addSql('ALTER TABLE matchups ADD CONSTRAINT FK_7B7433F33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE tournaments ADD CONSTRAINT FK_E4BCFAC35DFCD4B8 FOREIGN KEY (winner_id) REFERENCES players (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matchups DROP FOREIGN KEY FK_7B7433FC0990423');
        $this->addSql('ALTER TABLE matchups DROP FOREIGN KEY FK_7B7433FD22CABCD');
        $this->addSql('ALTER TABLE matchups DROP FOREIGN KEY FK_7B7433F5DFCD4B8');
        $this->addSql('ALTER TABLE matchups DROP FOREIGN KEY FK_7B7433F33D1A3E7');
        $this->addSql('ALTER TABLE tournaments DROP FOREIGN KEY FK_E4BCFAC35DFCD4B8');
        $this->addSql('DROP TABLE matchups');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE tournaments');
    }
}

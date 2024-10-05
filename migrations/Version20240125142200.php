<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240125142200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendance (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, check_in_date_time DATETIME NOT NULL, check_out_date_time DATETIME NOT NULL, INDEX IDX_6DE30D918C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE benefit (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, benefit_type VARCHAR(255) NOT NULL, coverage_details VARCHAR(255) NOT NULL, INDEX IDX_5C8B001F8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, department_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, date_of_birth DATETIME NOT NULL, contact_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, date_of_hire DATETIME NOT NULL, INDEX IDX_5D9F75A1AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee_training (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, training_program_id INT DEFAULT NULL, completion_date DATETIME DEFAULT NULL, INDEX IDX_452DDB488C03F15C (employee_id), INDEX IDX_452DDB488406BD6C (training_program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payroll (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, salary DOUBLE PRECISION NOT NULL, bonus DOUBLE PRECISION DEFAULT NULL, deductions DOUBLE PRECISION DEFAULT NULL, net_pay DOUBLE PRECISION NOT NULL, payroll_date DATE DEFAULT NULL, INDEX IDX_499FBCC68C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE performance_review (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, reviewer_id INT DEFAULT NULL, review_date DATETIME NOT NULL, rating INT NOT NULL, comment LONGTEXT NOT NULL, INDEX IDX_19ABAFA48C03F15C (employee_id), INDEX IDX_19ABAFA470574616 (reviewer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recruitment (id INT AUTO_INCREMENT NOT NULL, job_id INT DEFAULT NULL, employee_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_C7238C6EBE04EA9 (job_id), INDEX IDX_C7238C6E8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training_program (id INT AUTO_INCREMENT NOT NULL, program_name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attendance ADD CONSTRAINT FK_6DE30D918C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE benefit ADD CONSTRAINT FK_5C8B001F8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE employee_training ADD CONSTRAINT FK_452DDB488C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE employee_training ADD CONSTRAINT FK_452DDB488406BD6C FOREIGN KEY (training_program_id) REFERENCES training_program (id)');
        $this->addSql('ALTER TABLE payroll ADD CONSTRAINT FK_499FBCC68C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE performance_review ADD CONSTRAINT FK_19ABAFA48C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE performance_review ADD CONSTRAINT FK_19ABAFA470574616 FOREIGN KEY (reviewer_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE recruitment ADD CONSTRAINT FK_C7238C6EBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE recruitment ADD CONSTRAINT FK_C7238C6E8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attendance DROP FOREIGN KEY FK_6DE30D918C03F15C');
        $this->addSql('ALTER TABLE benefit DROP FOREIGN KEY FK_5C8B001F8C03F15C');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1AE80F5DF');
        $this->addSql('ALTER TABLE employee_training DROP FOREIGN KEY FK_452DDB488C03F15C');
        $this->addSql('ALTER TABLE employee_training DROP FOREIGN KEY FK_452DDB488406BD6C');
        $this->addSql('ALTER TABLE payroll DROP FOREIGN KEY FK_499FBCC68C03F15C');
        $this->addSql('ALTER TABLE performance_review DROP FOREIGN KEY FK_19ABAFA48C03F15C');
        $this->addSql('ALTER TABLE performance_review DROP FOREIGN KEY FK_19ABAFA470574616');
        $this->addSql('ALTER TABLE recruitment DROP FOREIGN KEY FK_C7238C6EBE04EA9');
        $this->addSql('ALTER TABLE recruitment DROP FOREIGN KEY FK_C7238C6E8C03F15C');
        $this->addSql('DROP TABLE attendance');
        $this->addSql('DROP TABLE benefit');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE employee_training');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE payroll');
        $this->addSql('DROP TABLE performance_review');
        $this->addSql('DROP TABLE recruitment');
        $this->addSql('DROP TABLE training_program');
    }
}

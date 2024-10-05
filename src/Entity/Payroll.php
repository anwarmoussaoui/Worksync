<?php

namespace App\Entity;

use App\Repository\PayrollRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PayrollRepository::class)]
class Payroll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payrolls')]
    private ?Employee $employee = null;

    #[ORM\Column]
    private ?float $salary = null;

    #[ORM\Column(nullable: true)]
    private ?float $bonus = null;

    #[ORM\Column(nullable: true)]
    private ?float $deductions = null;

    #[ORM\Column]
    private ?float $netPay = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\LessThanOrEqual("today")]
    private ?\DateTimeInterface $payrollDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getBonus(): ?float
    {
        return $this->bonus;
    }

    public function setBonus(?float $bonus): static
    {
        $this->bonus = $bonus;

        return $this;
    }

    public function getDeductions(): ?float
    {
        return $this->deductions;
    }

    public function setDeductions(?float $deductions): static
    {
        $this->deductions = $deductions;

        return $this;
    }

    public function getNetPay(): ?float
    {
        return $this->netPay;
    }

    public function setNetPay(float $netPay): static
    {
        $this->netPay = $netPay;

        return $this;
    }

    public function getPayrollDate(): ?\DateTimeInterface
    {
        return $this->payrollDate;
    }

    public function setPayrollDate(?\DateTimeInterface $payrollDate): static
    {
        $this->payrollDate = $payrollDate;

        return $this;
    }
}

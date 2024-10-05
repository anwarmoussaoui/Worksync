<?php

namespace App\Entity;

use App\Repository\BenefitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BenefitRepository::class)]
class Benefit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'benefits')]
    private ?Employee $employee = null;

    #[ORM\Column(length: 255)]
    private ?string $benefitType = null;

    #[ORM\Column(length: 255)]
    private ?string $coverageDetails = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBenefitType(): ?string
    {
        return $this->benefitType;
    }

    public function setBenefitType(string $benefitType): static
    {
        $this->benefitType = $benefitType;

        return $this;
    }

    public function getCoverageDetails(): ?string
    {
        return $this->coverageDetails;
    }

    public function setCoverageDetails(string $coverageDetails): static
    {
        $this->coverageDetails = $coverageDetails;

        return $this;
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
}

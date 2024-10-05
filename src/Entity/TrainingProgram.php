<?php

namespace App\Entity;

use App\Repository\TrainingProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrainingProgramRepository::class)]
class TrainingProgram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $programName = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\LessThanOrEqual("today")]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\GreaterThan(propertyPath: "startDate")]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\OneToMany(mappedBy: 'trainingProgram', targetEntity: EmployeeTraining::class)]
    private Collection $employeeTrainings;

    public function __construct()
    {
        $this->employeeTrainings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProgramName(): ?string
    {
        return $this->programName;
    }

    public function setProgramName(string $programName): static
    {
        $this->programName = $programName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection<int, EmployeeTraining>
     */
    public function getEmployeeTrainings(): Collection
    {
        return $this->employeeTrainings;
    }

    public function addEmployeeTraining(EmployeeTraining $employeeTraining): static
    {
        if (!$this->employeeTrainings->contains($employeeTraining)) {
            $this->employeeTrainings->add($employeeTraining);
            $employeeTraining->setTrainingProgram($this);
        }

        return $this;
    }

    public function removeEmployeeTraining(EmployeeTraining $employeeTraining): static
    {
        if ($this->employeeTrainings->removeElement($employeeTraining)) {
            // set the owning side to null (unless already changed)
            if ($employeeTraining->getTrainingProgram() === $this) {
                $employeeTraining->setTrainingProgram(null);
            }
        }

        return $this;
    }
}

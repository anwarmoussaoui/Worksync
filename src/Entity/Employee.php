<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 255)]
    private ?string $contactNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $address = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\LessThanOrEqual("today")]
    private ?\DateTimeInterface $dateOfHire = null;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Recruitment::class, cascade: ['remove'])]
    private Collection $recruitments;


    #[ORM\ManyToOne(inversedBy: 'employees')]
    private ?Department $department = null;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Benefit::class, cascade: ['remove'])]
    private Collection $benefits;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: PerformanceReview::class, cascade: ['remove'])]
    private Collection $performanceReviews;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Attendance::class, cascade: ['remove'])]
    private Collection $attendances;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: EmployeeTraining::class, cascade: ['remove'])]
    private Collection $employeeTrainings;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Payroll::class,cascade: ['remove'])]
    private Collection $payrolls;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    private ?Manager $manager = null;


    public function __construct()
    {
        $this->recruitments = new ArrayCollection();
        $this->benefits = new ArrayCollection();
        $this->performanceReviews = new ArrayCollection();
        $this->attendances = new ArrayCollection();
        $this->employeeTrainings = new ArrayCollection();
        $this->payrolls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getContactNumber(): ?string
    {
        return $this->contactNumber;
    }

    public function setContactNumber(string $contactNumber): static
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getDateOfHire(): ?\DateTimeInterface
    {
        return $this->dateOfHire;
    }

    public function setDateOfHire(\DateTimeInterface $dateOfHire): static
    {
        $this->dateOfHire = $dateOfHire;

        return $this;
    }

    /**
     * @return Collection<int, Recruitment>
     */
    public function getRecruitments(): Collection
    {
        return $this->recruitments;
    }

    public function addRecruitment(Recruitment $recruitment): static
    {
        if (!$this->recruitments->contains($recruitment)) {
            $this->recruitments->add($recruitment);
            $recruitment->setEmployee($this);
        }

        return $this;
    }

    public function removeRecruitment(Recruitment $recruitment): static
    {
        if ($this->recruitments->removeElement($recruitment)) {
            // set the owning side to null (unless already changed)
            if ($recruitment->getEmployee() === $this) {
                $recruitment->setEmployee(null);
            }
        }

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Benefit>
     */
    public function getBenefits(): Collection
    {
        return $this->benefits;
    }

    public function addBenefit(Benefit $benefit): static
    {
        if (!$this->benefits->contains($benefit)) {
            $this->benefits->add($benefit);
            $benefit->setEmployee($this);
        }

        return $this;
    }

    public function removeBenefit(Benefit $benefit): static
    {
        if ($this->benefits->removeElement($benefit)) {
            // set the owning side to null (unless already changed)
            if ($benefit->getEmployee() === $this) {
                $benefit->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PerformanceReview>
     */
    public function getPerformanceReviews(): Collection
    {
        return $this->performanceReviews;
    }

    public function addPerformanceReview(PerformanceReview $performanceReview): static
    {
        if (!$this->performanceReviews->contains($performanceReview)) {
            $this->performanceReviews->add($performanceReview);
            $performanceReview->setEmployee($this);
        }

        return $this;
    }

    public function removePerformanceReview(PerformanceReview $performanceReview): static
    {
        if ($this->performanceReviews->removeElement($performanceReview)) {
            // set the owning side to null (unless already changed)
            if ($performanceReview->getEmployee() === $this) {
                $performanceReview->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Attendance>
     */
    public function getAttendances(): Collection
    {
        return $this->attendances;
    }

    public function addAttendance(Attendance $attendance): static
    {
        if (!$this->attendances->contains($attendance)) {
            $this->attendances->add($attendance);
            $attendance->setEmployee($this);
        }

        return $this;
    }

    public function removeAttendance(Attendance $attendance): static
    {
        if ($this->attendances->removeElement($attendance)) {
            // set the owning side to null (unless already changed)
            if ($attendance->getEmployee() === $this) {
                $attendance->setEmployee(null);
            }
        }

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
            $employeeTraining->setEmployee($this);
        }

        return $this;
    }

    public function removeEmployeeTraining(EmployeeTraining $employeeTraining): static
    {
        if ($this->employeeTrainings->removeElement($employeeTraining)) {
            // set the owning side to null (unless already changed)
            if ($employeeTraining->getEmployee() === $this) {
                $employeeTraining->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payroll>
     */
    public function getPayrolls(): Collection
    {
        return $this->payrolls;
    }

    public function addPayroll(Payroll $payroll): static
    {
        if (!$this->payrolls->contains($payroll)) {
            $this->payrolls->add($payroll);
            $payroll->setEmployee($this);
        }

        return $this;
    }

    public function removePayroll(Payroll $payroll): static
    {
        if ($this->payrolls->removeElement($payroll)) {
            // set the owning side to null (unless already changed)
            if ($payroll->getEmployee() === $this) {
                $payroll->setEmployee(null);
            }
        }

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

}

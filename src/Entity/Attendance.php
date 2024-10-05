<?php

namespace App\Entity;

use App\Repository\AttendanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttendanceRepository::class)]
class Attendance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'attendances')]
    private ?Employee $employee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $checkInDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $checkOutDateTime = null;

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

    public function getCheckInDateTime(): ?\DateTimeInterface
    {
        return $this->checkInDateTime;
    }

    public function setCheckInDateTime(\DateTimeInterface $checkInDateTime): static
    {
        $this->checkInDateTime = $checkInDateTime;

        return $this;
    }

    public function getCheckOutDateTime(): ?\DateTimeInterface
    {
        return $this->checkOutDateTime;
    }

    public function setCheckOutDateTime(\DateTimeInterface $checkOutDateTime): static
    {
        $this->checkOutDateTime = $checkOutDateTime;

        return $this;
    }
}

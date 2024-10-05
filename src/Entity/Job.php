<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;


    #[ORM\OneToMany(mappedBy: 'job', targetEntity: Recruitment::class, cascade: ['remove'])]
    private Collection $recruitments;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->recruitments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

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
            $recruitment->setJob($this);
        }

        return $this;
    }

    public function removeRecruitment(Recruitment $recruitment): static
    {
        if ($this->recruitments->removeElement($recruitment)) {
            // set the owning side to null (unless already changed)
            if ($recruitment->getJob() === $this) {
                $recruitment->setJob(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}

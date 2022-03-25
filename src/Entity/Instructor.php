<?php

namespace App\Entity;

use App\Repository\InstructorRepository;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InstructorRepository::class)
 */
class Instructor extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $pseudo;

    /**
     * @ORM\ManyToOne(targetEntity=InstructorStatut::class, inversedBy="instructor")
     * @ORM\JoinColumn(nullable=false)
     */
    private $instructorStatut;

    /**
     * @ORM\ManyToMany(targetEntity=Speciality::class, mappedBy="instructor")
     */
    private $specialities;

    /**
     * @ORM\ManyToMany(targetEntity=Training::class, mappedBy="instructor")
     */
    private $trainings;

    public function __construct()
    {
        $this->specialities = new ArrayCollection();
        $this->trainings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getInstructorStatut(): ?InstructorStatut
    {
        return $this->instructorStatut;
    }

    public function setInstructorStatut(?InstructorStatut $instructorStatut): self
    {
        $this->instructorStatut = $instructorStatut;

        return $this;
    }

    /**
     * @return Collection<int, Speciality>
     */
    public function getSpecialities(): Collection
    {
        return $this->specialities;
    }

    public function addSpeciality(Speciality $speciality): self
    {
        if (!$this->specialities->contains($speciality)) {
            $this->specialities[] = $speciality;
            $speciality->addInstructor($this);
        }

        return $this;
    }

    public function removeSpeciality(Speciality $speciality): self
    {
        if ($this->specialities->removeElement($speciality)) {
            $speciality->removeInstructor($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Training>
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Training $training): self
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings[] = $training;
            $training->addInstructor($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): self
    {
        if ($this->trainings->removeElement($training)) {
            $training->removeInstructor($this);
        }

        return $this;
    }
}

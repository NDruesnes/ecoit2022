<?php

namespace App\Entity;

use App\Repository\SpecialityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpecialityRepository::class)
 */
class Speciality
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $speciality;

    /**
     * @ORM\ManyToMany(targetEntity=Instructor::class, inversedBy="specialities")
     */
    private $instructor;

    public function __construct()
    {
        $this->instructor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * @return Collection<int, Instructor>
     */
    public function getInstructor(): Collection
    {
        return $this->instructor;
    }

    public function addInstructor(Instructor $instructor): self
    {
        if (!$this->instructor->contains($instructor)) {
            $this->instructor[] = $instructor;
        }

        return $this;
    }

    public function removeInstructor(Instructor $instructor): self
    {
        $this->instructor->removeElement($instructor);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student extends User
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
     * @ORM\ManyToMany(targetEntity=Training::class, mappedBy="students")
     */
    private $trainings;

    /**
     * @ORM\ManyToOne(targetEntity=LessonStatut::class, inversedBy="student")
     */
    private $lessonStatut;

    public function __construct()
    {
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
            $training->addStudent($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): self
    {
        if ($this->trainings->removeElement($training)) {
            $training->removeStudent($this);
        }

        return $this;
    }

    public function getLessonStatut(): ?LessonStatut
    {
        return $this->lessonStatut;
    }

    public function setLessonStatut(?LessonStatut $lessonStatut): self
    {
        $this->lessonStatut = $lessonStatut;

        return $this;
    }
}

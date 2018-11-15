<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityShowsRepository")
 */
class EntityShows
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $sheet_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EntityPerformances", mappedBy="shows", orphanRemoval=true)
     */
    private $entityPerformances;

    public function __construct()
    {
        $this->entityPerformances = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getSheetId(): ?int
    {
        return $this->sheet_id;
    }

    public function setSheetId(int $sheet_id): self
    {
        $this->sheet_id = $sheet_id;

        return $this;
    }

    /**
     * @return Collection|EntityPerformances[]
     */
    public function getEntityPerformances(): Collection
    {
        return $this->entityPerformances;
    }

    public function addEntityPerformance(EntityPerformances $entityPerformance): self
    {
        if (!$this->entityPerformances->contains($entityPerformance)) {
            $this->entityPerformances[] = $entityPerformance;
            $entityPerformance->setShows($this);
        }

        return $this;
    }

    public function removeEntityPerformance(EntityPerformances $entityPerformance): self
    {
        if ($this->entityPerformances->contains($entityPerformance)) {
            $this->entityPerformances->removeElement($entityPerformance);
            // set the owning side to null (unless already changed)
            if ($entityPerformance->getShows() === $this) {
                $entityPerformance->setShows(null);
            }
        }

        return $this;
    }
}

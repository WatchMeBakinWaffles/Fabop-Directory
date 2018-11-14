<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityInstitutionsRepository")
 */
class EntityInstitutions
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EntityPeople", mappedBy="institutions_id", orphanRemoval=true)
     */
    private $entityPeople;

    public function __construct()
    {
        $this->entityPeople = new ArrayCollection();
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|EntityPeople[]
     */
    public function getEntityPeople(): Collection
    {
        return $this->entityPeople;
    }

    public function addEntityPerson(EntityPeople $entityPerson): self
    {
        if (!$this->entityPeople->contains($entityPerson)) {
            $this->entityPeople[] = $entityPerson;
            $entityPerson->setInstitutionsId($this);
        }

        return $this;
    }

    public function removeEntityPerson(EntityPeople $entityPerson): self
    {
        if ($this->entityPeople->contains($entityPerson)) {
            $this->entityPeople->removeElement($entityPerson);
            // set the owning side to null (unless already changed)
            if ($entityPerson->getInstitutionsId() === $this) {
                $entityPerson->setInstitutionsId(null);
            }
        }

        return $this;
    }
}

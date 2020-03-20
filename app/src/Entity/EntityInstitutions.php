<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityInstitutionsRepository")
 * @ApiResource(
 *      attributes={"access_control"="is_granted('ROLE_USER')"},
 *      collectionOperations={
 *          "get",
 *      },
 *      itemOperations={
 *          "get",
 *          "put"={"access_control"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN')"}
 *      }
 * )
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
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $sheet_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EntityPeople", mappedBy="institution", orphanRemoval=true)
     */
    private $entityPeople;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EntityUser", mappedBy="institution")
     */
    private $entityUsers;


    public function __construct()
    {
        $this->entityPeople = new ArrayCollection();
        $this->entityUsers = new ArrayCollection();
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getSheetId(): ?string
    {
        return $this->sheet_id;
    }

    public function setSheetId(string $sheet_id): self
    {
        $this->sheet_id = $sheet_id;

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
            $entityPerson->setInstitution($this);
        }

        return $this;
    }

    public function removeEntityPerson(EntityPeople $entityPerson): self
    {
        if ($this->entityPeople->contains($entityPerson)) {
            $this->entityPeople->removeElement($entityPerson);
            // set the owning side to null (unless already changed)
            if ($entityPerson->getInstitution() === $this) {
                $entityPerson->setInstitution(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EntityUser[]
     */
    public function getEntityUsers(): Collection
    {
        return $this->entityUsers;
    }

    public function addEntityUser(EntityUser $entityUser): self
    {
        if (!$this->entityUsers->contains($entityUser)) {
            $this->entityUsers[] = $entityUser;
            $entityUser->setInstitution($this);
        }

        return $this;
    }

    public function removeEntityUser(EntityUser $entityUser): self
    {
        if ($this->entityUsers->contains($entityUser)) {
            $this->entityUsers->removeElement($entityUser);
            // set the owning side to null (unless already changed)
            if ($entityUser->getInstitution() === $this) {
                $entityUser->setInstitution(null);
            }
        }

        return $this;
    }
}

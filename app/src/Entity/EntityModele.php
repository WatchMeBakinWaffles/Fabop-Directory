<?php

namespace App\Entity;

use App\Repository\EntityModeleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntityModeleRepository::class)
 */
class EntityModele
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $modele_default;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $modele_custom;

    /**
     * @ORM\ManyToMany(targetEntity=EntityUser::class, inversedBy="modeles")
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModeleDefault(): ?bool
    {
        return $this->modele_default;
    }

    public function setModeleDefault(bool $modele_default): self
    {
        $this->modele_default = $modele_default;

        return $this;
    }

    public function getModeleCustom(): ?bool
    {
        return $this->modele_custom;
    }

    public function setModeleCustom(?bool $modele_custom): self
    {
        $this->modele_custom = $modele_custom;

        return $this;
    }

    /**
     * @return Collection|EntityUser[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(EntityUser $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(EntityUser $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }
}

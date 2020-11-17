<?php

namespace App\Entity;

use App\Repository\EntityRolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntityRolesRepository::class)
 */
class EntityRoles
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
    private $editable;

    /**
     * @ORM\ManyToMany(targetEntity=EntityUser::class, inversedBy="entityRoles")
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToOne(targetEntity=Permissions::class, mappedBy="role_id", cascade={"persist", "remove"})
     */
    private $permissions;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEditable(): ?bool
    {
        return $this->editable;
    }

    public function setEditable(bool $editable): self
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * @return Collection|EntityUser[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(EntityUser $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(EntityUser $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPermissions(): ?Permissions
    {
        return $this->permissions;
    }

    public function setPermissions(?Permissions $permissions): self
    {
        $this->permissions = $permissions;

        // set (or unset) the owning side of the relation if necessary
        $newRole_id = null === $permissions ? null : $this;
        if ($permissions->getRoleId() !== $newRole_id) {
            $permissions->setRoleId($newRole_id);
        }

        return $this;
    }
}

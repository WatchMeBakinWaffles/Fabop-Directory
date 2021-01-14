<?php

namespace App\Entity;

use App\Repository\PermissionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PermissionsRepository::class)
 */
class Permissions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $sheet_id;

    /**
     * @ORM\OneToOne(targetEntity=EntityRoles::class, inversedBy="permissions", cascade={"persist", "remove"})
     */
    private $role_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRoleId(): ?EntityRoles
    {
        return $this->role_id;
    }

    public function setRoleId(?EntityRoles $role_id): self
    {
        $this->role_id = $role_id;

        return $this;
    }
}

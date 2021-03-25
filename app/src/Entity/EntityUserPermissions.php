<?php

namespace App\Entity;

use App\Repository\UserPermissionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=App\Repository\EntityUserPermissionsRepository::class)
 */
class EntityUserPermissions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=EntityUser::class, inversedBy="entityPermissions", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $sheet_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?EntityUser
    {
        return $this->user;
    }

    public function setUser(?EntityUser $user): self
    {
        $this->user = $user;

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
}

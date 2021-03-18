<?php

namespace App\Entity;

use App\Repository\UserPermissionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserPermissionsRepository::class)
 */
class UserPermissions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=EntityUser::class, inversedBy="UserPermissions", cascade={"persist", "remove"})
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

    public function getUserId(): ?EntityRoles
    {
        return $this->user_id;
    }

    public function setUserId(?EntityRoles $user_id): self
    {
        $this->user = $user_id;

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

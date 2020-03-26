<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *      },
 *      itemOperations={
 *          "get"={"access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_CONTRIBUTEUR') && (object.getInstitution() == user.getInstitution()) )"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_CONTRIBUTEUR') && (object.getInstitution() == user.getInstitution()) )"}
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\LogRepository")
 */
class Log
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $element;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_action;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $institution;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getElement(): ?string
    {
        return $this->element;
    }

    public function setElement(string $element): self
    {
        $this->element = $element;

        return $this;
    }

    public function getTypeAction(): ?string
    {
        return $this->type_action;
    }

    public function setTypeAction(string $type_action): self
    {
        $this->type_action = $type_action;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getInstitution(): ?int
    {
        return $this->institution;
    }

    public function setInstitution(?int $institution): self
    {
        $this->institution = $institution;

        return $this;
    }
}

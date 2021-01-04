<?php

namespace App\Entity;

use App\Repository\EntityModeleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=EntityModeleRepository::class)
 * @Vich\Uploadable()
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
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $modele_custom;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $name;


    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="user_modeles", fileNameProperty="filename")
     */
    private $modeleFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\ManyToMany(targetEntity=EntityUser::class, inversedBy="modeles")
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime();
    }

    public function __toString(){
        return $this->getModeleDefault()." ".$this->getModeleCustom()." ".$this->getUser();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return File|null
     */
    public function getModeleFile(): ?File
    {
        return $this->modeleFile;
    }

    public function setModeleFile(?File $modeleFile)
    {
        $this->modeleFile = $modeleFile;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }


    public function setFilename(?string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return EntityModele
     */
    public function setCreatedAt(\DateTime $createdAt): EntityModele
    {
        $this->createdAt = $createdAt;
        return $this;
    }


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updatedAt = $updated_at;

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

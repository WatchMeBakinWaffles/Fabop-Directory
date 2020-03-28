<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityPeopleRepository")
 * @ApiResource(
 *      attributes={"access_control"="is_granted('ROLE_USER')"},
 *      collectionOperations={
 *          "get"={"object.getId() == user.getInstitution()"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_CONTRIBUTEUR') && (object.getInstitution() == user.getInstitution()) )"},
 *      },
 *      itemOperations={
 *          "get"={"object.getId() == user.getInstitution()"},
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_CONTRIBUTEUR') && (object.getInstitution() == user.getInstitution()) )"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_CONTRIBUTEUR') && (object.getInstitution() == user.getInstitution()) )"}
 *      }
 * )
 */
class EntityPeople
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
    private $firstname;

    /**
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $newsletter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="date")
     */
    private $add_date;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $sheet_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TagsAffect", mappedBy="person", orphanRemoval=true)
     */
    private $tagsAffects;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EntityInstitutions", inversedBy="entityPeople")
     * @ORM\JoinColumn(nullable=false)
     * @var institution
     */
    private $institution;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresseMailing;

    public function __construct()
    {
        $this->tagsAffects = new ArrayCollection();
    }

    public function __toString() {
        return $this->name." ".$this->firstname;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->add_date;
    }

    public function setAddDate(\DateTimeInterface $add_date): self
    {
        $this->add_date = $add_date;

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
     * @return Collection|TagsAffect[]
     */
    public function getTagsAffects(): Collection
    {
        return $this->tagsAffects;
    }

    public function addTagsAffect(TagsAffect $tagsAffect): self
    {
        if (!$this->tagsAffects->contains($tagsAffect)) {
            $this->tagsAffects[] = $tagsAffect;
            $tagsAffect->setPerson($this);
        }

        return $this;
    }

    public function removeTagsAffect(TagsAffect $tagsAffect): self
    {
        if ($this->tagsAffects->contains($tagsAffect)) {
            $this->tagsAffects->removeElement($tagsAffect);
            // set the owning side to null (unless already changed)
            if ($tagsAffect->getPerson() === $this) {
                $tagsAffect->setPerson(null);
            }
        }

        return $this;
    }

    public function getInstitution(): ?EntityInstitutions
    {
        return $this->institution;
    }

    public function setInstitution(?EntityInstitutions $institution): self
    {
        $this->institution = $institution;

        return $this;
    }

    public function getAdresseMailing(): ?string
    {
        return $this->adresseMailing;
    }

    public function setAdresseMailing(?string $adresseMailing): self
    {
        $this->adresseMailing = $adresseMailing;

        return $this;
    }

}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityUserRepository")
 */
class EntityUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $ApiToken;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EntityInstitutions", inversedBy="entityUsers")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $institution;

    /**
     * @ORM\ManyToMany(targetEntity=EntityRoles::class, mappedBy="users")
     */
    private $entityRoles;

    public function __construct()
    {
        $this->entityRoles = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->ApiToken;
    }

    public function setApiToken(string $ApiToken): self
    {
        $this->ApiToken = $ApiToken;

        return $this;
    }

    public function bCryptPassword(string $password){
        $crypted = password_hash($password, PASSWORD_BCRYPT);
        $this->setPassword($crypted);


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

    /**
     * @return Collection|EntityRoles[]
     */
    public function getEntityRoles(): Collection
    {
        return $this->entityRoles;
    }

    public function addEntityRole(EntityRoles $entityRole): self
    {
        if (!$this->entityRoles->contains($entityRole)) {
            $this->entityRoles[] = $entityRole;
            $entityRole->addUser($this);
        }

        return $this;
    }

    public function removeEntityRole(EntityRoles $entityRole): self
    {
        if ($this->entityRoles->removeElement($entityRole)) {
            $entityRole->removeUser($this);
        }

        return $this;
    }

 
}

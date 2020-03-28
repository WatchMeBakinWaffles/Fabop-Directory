<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagsAffectRepository")
 * @ApiResource()
 */
class TagsAffect
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EntityTags", inversedBy="tagsAffects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tag;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EntityPerformances", inversedBy="tagsAffects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $performance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EntityPeople", inversedBy="tagsAffects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTag(): ?EntityTags
    {
        return $this->tag;
    }

    public function setTag(?EntityTags $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getPerformance(): ?EntityPerformances
    {
        return $this->performance;
    }

    public function setPerformance(?EntityPerformances $performance): self
    {
        $this->performance = $performance;

        return $this;
    }

    public function getPerson(): ?EntityPeople
    {
        return $this->person;
    }

    public function setPerson(?EntityPeople $person): self
    {
        $this->person = $person;

        return $this;
    }
}

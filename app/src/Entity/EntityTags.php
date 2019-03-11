<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityTagsRepository")
 */
class EntityTags
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
     * @ORM\OneToMany(targetEntity="App\Entity\TagsAffect", mappedBy="tag", orphanRemoval=true)
     */
    private $tagsAffects;

    public function __construct()
    {
        $this->tagsAffects = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
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
            $tagsAffect->setTag($this);
        }

        return $this;
    }

    public function removeTagsAffect(TagsAffect $tagsAffect): self
    {
        if ($this->tagsAffects->contains($tagsAffect)) {
            $this->tagsAffects->removeElement($tagsAffect);
            // set the owning side to null (unless already changed)
            if ($tagsAffect->getTag() === $this) {
                $tagsAffect->setTag(null);
            }
        }

        return $this;
    }

}

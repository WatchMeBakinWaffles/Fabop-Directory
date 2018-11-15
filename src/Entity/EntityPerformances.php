<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntityPerformancesRepository")
 */
class EntityPerformances
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EntityShows", inversedBy="entityPerformances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shows;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TagsAffect", mappedBy="performance", orphanRemoval=true)
     */
    private $tagsAffects;

    public function __construct()
    {
        $this->tagsAffects = new ArrayCollection();
    }

    public function __toString() {
        return strval($this->shows);
    }

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

    public function getShows(): ?EntityShows
    {
        return $this->shows;
    }

    public function setShows(?EntityShows $shows): self
    {
        $this->shows = $shows;

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
            $tagsAffect->setPerformance($this);
        }

        return $this;
    }

    public function removeTagsAffect(TagsAffect $tagsAffect): self
    {
        if ($this->tagsAffects->contains($tagsAffect)) {
            $this->tagsAffects->removeElement($tagsAffect);
            // set the owning side to null (unless already changed)
            if ($tagsAffect->getPerformance() === $this) {
                $tagsAffect->setPerformance(null);
            }
        }

        return $this;
    }

}
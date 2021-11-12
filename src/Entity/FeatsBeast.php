<?php

namespace App\Entity;

use App\Repository\FeatsBeastRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeatsBeastRepository::class)
 */
class FeatsBeast
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToMany(targetEntity=Beast::class, mappedBy="feats")
     */
    private $beasts;

    public function __construct()
    {
        $this->beasts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|Beast[]
     */
    public function getBeasts(): Collection
    {
        return $this->beasts;
    }

    public function addBeast(Beast $beast): self
    {
        if (!$this->beasts->contains($beast)) {
            $this->beasts[] = $beast;
            $beast->addFeat($this);
        }

        return $this;
    }

    public function removeBeast(Beast $beast): self
    {
        if ($this->beasts->removeElement($beast)) {
            $beast->removeFeat($this);
        }

        return $this;
    }
}

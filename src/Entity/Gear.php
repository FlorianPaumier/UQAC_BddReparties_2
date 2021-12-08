<?php

namespace App\Entity;

use App\Repository\GearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GearRepository::class)
 */
class Gear
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
     * @ORM\ManyToMany(targetEntity=Beast::class, inversedBy="gears")
     */
    private $beasts;

    public function __toString(
    ): string
    {
       return $this->getValue();
    }

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
        }

        return $this;
    }

    public function removeBeast(Beast $beast): self
    {
        $this->beasts->removeElement($beast);

        return $this;
    }
}

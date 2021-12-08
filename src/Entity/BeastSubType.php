<?php

namespace App\Entity;

use App\Repository\BeastSubTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=BeastSubTypeRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class BeastSubType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups({"subType", "subType_light"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"subType", "subType_light"})
     */
    private ?string $name;

    /**
     * @ORM\ManyToMany(targetEntity=Beast::class, mappedBy="subTypes")
     * @Serializer\Expose()
     * @Serializer\Groups({"subType"})
     */
    private Collection $beasts;

    public function __toString(
    ): string
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->beasts = new ArrayCollection();
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
            $beast->addSubType($this);
        }

        return $this;
    }

    public function removeBeast(Beast $beast): self
    {
        if ($this->beasts->removeElement($beast)) {
            $beast->removeSubType($this);
        }

        return $this;
    }
}

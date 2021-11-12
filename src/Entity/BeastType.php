<?php

namespace App\Entity;

use App\Repository\BeastTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=BeastTypeRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class BeastType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups({"type", "type_light"})
     */
    private ?int $id;

    /**
     * @ORM\Column (type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"type", "type_light"})
     */
    private ?string $value;

    /**
     * @ORM\OneToMany(targetEntity=Beast::class, mappedBy="type")
     * @Serializer\Expose()
     * @Serializer\Groups({"type"})
     */
    private Collection $beasts;

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
            $beast->setType($this);
        }

        return $this;
    }

    public function removeBeast(Beast $beast): self
    {
        if ($this->beasts->removeElement($beast)) {
            // set the owning side to null (unless already changed)
            if ($beast->getType() === $this) {
                $beast->setType(null);
            }
        }

        return $this;
    }

}

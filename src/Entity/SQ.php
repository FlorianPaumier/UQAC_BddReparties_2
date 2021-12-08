<?php

namespace App\Entity;

use App\Repository\SQRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SQRepository::class)
 */
class SQ
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
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $level;

    /**
     * @ORM\ManyToMany(targetEntity=Beast::class, inversedBy="sQs")
     */
    private $beast;

    public function __toString(
    ): string
    {
        return $this->getName();
    }

    public function __construct()
    {
        $this->beast = new ArrayCollection();
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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|Beast[]
     */
    public function getBeast(): Collection
    {
        return $this->beast;
    }

    public function addBeast(Beast $beast): self
    {
        if (!$this->beast->contains($beast)) {
            $this->beast[] = $beast;
        }

        return $this;
    }

    public function removeBeast(Beast $beast): self
    {
        $this->beast->removeElement($beast);

        return $this;
    }
}

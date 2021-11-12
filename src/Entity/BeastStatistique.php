<?php

namespace App\Entity;

use App\Repository\BeastStatistiqueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeastStatistiqueRepository::class)
 */
class BeastStatistique
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
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Beast::class, inversedBy="beastStatistiques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $beast;

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

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getBeast(): ?Beast
    {
        return $this->beast;
    }

    public function setBeast(?Beast $beast): self
    {
        $this->beast = $beast;

        return $this;
    }
}

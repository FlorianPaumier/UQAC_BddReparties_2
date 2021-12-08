<?php

namespace App\Entity;

use App\Repository\BeastSkillsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeastSkillsRepository::class)
 */
class BeastSkills
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
     * @ORM\ManyToOne(targetEntity=Beast::class, inversedBy="beastSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $beast;

    public function __toString(
    ): string
    {
        return $this->getValue();
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

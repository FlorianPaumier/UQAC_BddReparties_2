<?php

namespace App\Entity;

use App\Repository\ClassSpellRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClassSpellRepository::class)
 */
class ClassSpell
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity=ClassType::class, inversedBy="classSpells")
     * @ORM\JoinColumn(nullable=false)
     */
    private $classType;

    /**
     * @ORM\ManyToOne(targetEntity=Spell::class, inversedBy="classSpells")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spell;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClassType(): ?ClassType
    {
        return $this->classType;
    }

    public function setClassType(?ClassType $classType): self
    {
        $this->classType = $classType;

        return $this;
    }

    public function getSpell(): ?Spell
    {
        return $this->spell;
    }

    public function setSpell(?Spell $spell): self
    {
        $this->spell = $spell;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ClassTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClassTypeRepository::class)
 */
class ClassType
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
     * @ORM\OneToMany(targetEntity=ClassSpell::class, mappedBy="classType", orphanRemoval=true)
     */
    private $classSpells;

    public function __construct()
    {
        $this->classSpells = new ArrayCollection();
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
     * @return Collection|ClassSpell[]
     */
    public function getClassSpells(): Collection
    {
        return $this->classSpells;
    }

    public function addClassSpell(ClassSpell $classSpell): self
    {
        if (!$this->classSpells->contains($classSpell)) {
            $this->classSpells[] = $classSpell;
            $classSpell->setClassType($this);
        }

        return $this;
    }

    public function removeClassSpell(ClassSpell $classSpell): self
    {
        if ($this->classSpells->removeElement($classSpell)) {
            // set the owning side to null (unless already changed)
            if ($classSpell->getClassType() === $this) {
                $classSpell->setClassType(null);
            }
        }

        return $this;
    }
}

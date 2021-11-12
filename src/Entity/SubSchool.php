<?php

namespace App\Entity;

use App\Repository\SubSchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=SubSchoolRepository::class)
 * @Serializer\ExclusionPolicy("all")
 */
class SubSchool
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"subSchool_light", "subSchool"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"subSchool_light", "subSchool"})
     */
    private $name;

    /**
     * @Serializer\Expose()
     * @Serializer\Groups({"subSchool"})
     * @ORM\OneToMany(targetEntity=Spell::class, mappedBy="subSchool")
     */
    private $spell;

    public function __construct()
    {
        $this->spell = new ArrayCollection();
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
     * @return Collection|Spell[]
     */
    public function getSpell(): Collection
    {
        return $this->spell;
    }

    public function addSpell(Spell $spell): self
    {
        if (!$this->spell->contains($spell)) {
            $this->spell[] = $spell;
            $spell->setSubSchool($this);
        }

        return $this;
    }

    public function removeSpell(Spell $spell): self
    {
        if ($this->spell->removeElement($spell)) {
            // set the owning side to null (unless already changed)
            if ($spell->getSubSchool() === $this) {
                $spell->setSubSchool(null);
            }
        }

        return $this;
    }
}

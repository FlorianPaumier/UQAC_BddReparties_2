<?php

namespace App\Entity;

use App\Repository\SpellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=SpellRepository::class)
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Table()
 */
class Spell
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups({"spell", "spell_light"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell", "spell_light"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell", "spell_light"})
     */
    private ?string $description;

    /**
     * @ORM\OneToMany(targetEntity=ClassSpell::class, mappedBy="spell", orphanRemoval=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell", "spell_light"})
     */
    private Collection $classSpells;

    /**
     * @ORM\ManyToMany(targetEntity=Beast::class, mappedBy="spells")
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private Collection $beasts;

    /**
     * @ORM\ManyToOne(targetEntity=School::class, inversedBy="spells")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell", "spell_light"})
     */
    private ?School $school;

    /**
     * @ORM\ManyToOne(targetEntity=SubSchool::class, inversedBy="spell")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell", "spell_light"})
     */
    private ?SubSchool $subSchool;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?string $desciptor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?int $costlyComponents;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?string $range;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?string $area;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?string $effect;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?string $targets;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?string $duration;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?int $dismissible;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?int $shapeable;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?string $savingThrow;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?string $spellResistance;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose()
     * @Serializer\Groups({"spell", "spell_light"})
     */
    private ?string $shortDescription;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private ?string $castingTime;

    /**
     * @ORM\OneToMany(targetEntity=Component::class, mappedBy="spell")
     * @Serializer\Expose()
     * @Serializer\Groups({"spell"})
     */
    private Collection $components;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Serializer\Expose()
     * @Serializer\Groups({"spell", "spell_light"})
     */
    private ?string $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $domain;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $divineFocus;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $focus;

    /**
     * @var string
     * @ORM\Column(name="documents", type="tsvector")
     */
    private $documents = "";

    public function __construct()
    {
        $this->classSpells = new ArrayCollection();
        $this->beasts = new ArrayCollection();
        $this->components = new ArrayCollection();
    }

    public function __toString(
    ): string
    {
      return $this->name;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
            $classSpell->setSpell($this);
        }

        return $this;
    }

    public function removeClassSpell(ClassSpell $classSpell): self
    {
        if ($this->classSpells->removeElement($classSpell)) {
            // set the owning side to null (unless already changed)
            if ($classSpell->getSpell() === $this) {
                $classSpell->setSpell(null);
            }
        }

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
            $beast->addSpell($this);
        }

        return $this;
    }

    public function removeBeast(Beast $beast): self
    {
        if ($this->beasts->removeElement($beast)) {
            $beast->removeSpell($this);
        }

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getSubSchool(): ?SubSchool
    {
        return $this->subSchool;
    }

    public function setSubSchool(?SubSchool $subSchool): self
    {
        $this->subSchool = $subSchool;

        return $this;
    }

    public function getDesciptor(): ?string
    {
        return $this->desciptor;
    }

    public function setDesciptor(?string $desciptor): self
    {
        $this->desciptor = $desciptor;

        return $this;
    }

    public function getCostlyComponents(): ?int
    {
        return $this->costlyComponents;
    }

    public function setCostlyComponents(?int $costlyComponents): self
    {
        $this->costlyComponents = $costlyComponents;

        return $this;
    }

    public function getRange(): ?string
    {
        return $this->range;
    }

    public function setRange(?string $range): self
    {
        $this->range = $range;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(?string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getEffect(): ?string
    {
        return $this->effect;
    }

    public function setEffect(string $effect): self
    {
        $this->effect = $effect;

        return $this;
    }

    public function getTargets(): ?string
    {
        return $this->targets;
    }

    public function setTargets(?string $targets): self
    {
        $this->targets = $targets;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDismissible(): ?int
    {
        return $this->dismissible;
    }

    public function setDismissible(int $dismissible): self
    {
        $this->dismissible = $dismissible;

        return $this;
    }

    public function getShapeable(): ?int
    {
        return $this->shapeable;
    }

    public function setShapeable(int $shapeable): self
    {
        $this->shapeable = $shapeable;

        return $this;
    }

    public function getSavingThrow(): ?string
    {
        return $this->savingThrow;
    }

    public function setSavingThrow(string $savingThrow): self
    {
        $this->savingThrow = $savingThrow;

        return $this;
    }

    public function getSpellResistance(): ?string
    {
        return $this->spellResistance;
    }

    public function setSpellResistance(string $spellResistance): self
    {
        $this->spellResistance = $spellResistance;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getCastingTime(): ?string
    {
        return $this->castingTime;
    }

    public function setCastingTime(string $castingTime): self
    {
        $this->castingTime = $castingTime;

        return $this;
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
            $component->setSpell($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->removeElement($component)) {
            // set the owning side to null (unless already changed)
            if ($component->getSpell() === $this) {
                $component->setSpell(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getFocus(): ?bool
    {
        return $this->focus;
    }

    public function setFocus(?bool $focus): self
    {
        $this->focus = $focus;

        return $this;
    }

    /**
     * @return string
     */
    public function getDocuments(
    ): string
    {
        return $this->documents;
    }

    /**
     * @param $documents
     * @return Spell
     */
    public function setDocuments(
        $documents
    ): Spell {
        $this->documents = $documents;
        return $this;
    }

    public function getDivineFocus(): ?bool
    {
        return $this->divineFocus;
    }

    public function setDivineFocus(?bool $divineFocus): self
    {
        $this->divineFocus = $divineFocus;

        return $this;
    }
}

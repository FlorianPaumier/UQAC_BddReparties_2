<?php

namespace App\Entity;

use App\Repository\BeastRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeastRepository::class)
 */
class Beast
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $xp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Spell::class, inversedBy="beasts")
     */
    private $spells;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=BeastStatistique::class, mappedBy="beast", orphanRemoval=true)
     */
    private $beastStatistiques;

    /**
     * @ORM\OneToMany(targetEntity=BeastSkills::class, mappedBy="beast", orphanRemoval=true)
     */
    private $beastSkills;

    /**
     * @ORM\ManyToMany(targetEntity=FeatsBeast::class, inversedBy="beasts")
     */
    private $feats;

    /**
     * @ORM\ManyToOne(targetEntity=BeastType::class, inversedBy="value")
     * @ORM\JoinColumn(nullable=true)
     */
    private $beastType;

    /**
     * @ORM\ManyToMany(targetEntity=BeastSubType::class, inversedBy="beasts")
     */
    private $subTypes;

    /**
     * @ORM\Column(type="float")
     */
    private $cr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alignment;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $size;

    /**
     * @ORM\ManyToOne(targetEntity=BeastType::class, inversedBy="beasts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $ac;

    /**
     * @ORM\Column(type="float")
     */
    private $hp;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $hd;

    /**
     * @ORM\Column(type="text")
     */
    private $melee;

    /**
     * @ORM\Column(type="text")
     */
    private $ranged;

    /**
     * @ORM\Column(type="text")
     */
    private $space;

    /**
     * @ORM\Column(type="text")
     */
    private $reach;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
        $this->beastStatistiques = new ArrayCollection();
        $this->beastSkills = new ArrayCollection();
        $this->feats = new ArrayCollection();
        $this->subTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getXp(): ?int
    {
        return $this->xp;
    }

    public function setXp(?int $xp): self
    {
        $this->xp = $xp;

        return $this;
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
    public function getSpells(): Collection
    {
        return $this->spells;
    }

    public function addSpell(Spell $spell): self
    {
        if (!$this->spells->contains($spell)) {
            $this->spells[] = $spell;
        }

        return $this;
    }

    public function removeSpell(Spell $spell): self
    {
        $this->spells->removeElement($spell);

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
     * @return Collection|BeastStatistique[]
     */
    public function getBeastStatistiques(): Collection
    {
        return $this->beastStatistiques;
    }

    public function addBeastStatistique(BeastStatistique $beastStatistique): self
    {
        if (!$this->beastStatistiques->contains($beastStatistique)) {
            $this->beastStatistiques[] = $beastStatistique;
            $beastStatistique->setBeast($this);
        }

        return $this;
    }

    public function removeBeastStatistique(BeastStatistique $beastStatistique): self
    {
        if ($this->beastStatistiques->removeElement($beastStatistique)) {
            // set the owning side to null (unless already changed)
            if ($beastStatistique->getBeast() === $this) {
                $beastStatistique->setBeast(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BeastSkills[]
     */
    public function getBeastSkills(): Collection
    {
        return $this->beastSkills;
    }

    public function addBeastSkill(BeastSkills $beastSkill): self
    {
        if (!$this->beastSkills->contains($beastSkill)) {
            $this->beastSkills[] = $beastSkill;
            $beastSkill->setBeast($this);
        }

        return $this;
    }

    public function removeBeastSkill(BeastSkills $beastSkill): self
    {
        if ($this->beastSkills->removeElement($beastSkill)) {
            // set the owning side to null (unless already changed)
            if ($beastSkill->getBeast() === $this) {
                $beastSkill->setBeast(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FeatsBeast[]
     */
    public function getFeats(): Collection
    {
        return $this->feats;
    }

    public function addFeat(FeatsBeast $feat): self
    {
        if (!$this->feats->contains($feat)) {
            $this->feats[] = $feat;
        }

        return $this;
    }

    public function removeFeat(FeatsBeast $feat): self
    {
        $this->feats->removeElement($feat);

        return $this;
    }

    public function getBeastType(): ?BeastType
    {
        return $this->beastType;
    }

    public function setBeastType(?BeastType $beastType): self
    {
        $this->beastType = $beastType;

        return $this;
    }

    /**
     * @return Collection|BeastSubType[]
     */
    public function getSubTypes(): Collection
    {
        return $this->subTypes;
    }

    public function addSubType(BeastSubType $subType): self
    {
        if (!$this->subTypes->contains($subType)) {
            $this->subTypes[] = $subType;
        }

        return $this;
    }

    public function removeSubType(BeastSubType $subType): self
    {
        $this->subTypes->removeElement($subType);

        return $this;
    }

    public function getCr(): ?float
    {
        return $this->cr;
    }

    public function setCr(float $cr): self
    {
        $this->cr = $cr;

        return $this;
    }

    public function getAlignment(): ?string
    {
        return $this->alignment;
    }

    public function setAlignment(string $alignment): self
    {
        $this->alignment = $alignment;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getType(): ?BeastType
    {
        return $this->type;
    }

    public function setType(?BeastType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAc(): ?float
    {
        return $this->ac;
    }

    public function setAc(float $ac): self
    {
        $this->ac = $ac;

        return $this;
    }

    public function getHp(): ?float
    {
        return $this->hp;
    }

    public function setHp(float $hp): self
    {
        $this->hp = $hp;

        return $this;
    }

    public function getHd(): ?string
    {
        return $this->hd;
    }

    public function setHd(string $hd): self
    {
        $this->hd = $hd;

        return $this;
    }

    public function getMelee(): ?string
    {
        return $this->melee;
    }

    public function setMelee(string $melee): self
    {
        $this->melee = $melee;

        return $this;
    }

    public function getRanged(): ?string
    {
        return $this->ranged;
    }

    public function setRanged(string $ranged): self
    {
        $this->ranged = $ranged;

        return $this;
    }

    public function getSpace(): ?string
    {
        return $this->space;
    }

    public function setSpace(string $space): self
    {
        $this->space = $space;

        return $this;
    }

    public function getReach(): ?string
    {
        return $this->reach;
    }

    public function setReach(string $reach): self
    {
        $this->reach = $reach;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\BeastRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BeastRepository::class)
 * @ORM\Table()
 */
class Beast
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $xp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\ManyToMany(targetEntity=Spell::class, inversedBy="beasts")
     */
    private Collection $spells;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\OneToMany(targetEntity=BeastStatistique::class, mappedBy="beast", orphanRemoval=true)
     */
    private Collection $beastStatistiques;

    /**
     * @ORM\OneToMany(targetEntity=BeastSkills::class, mappedBy="beast", orphanRemoval=true)
     */
    private Collection $beastSkills;

    /**
     * @ORM\ManyToMany(targetEntity=FeatsBeast::class, inversedBy="beasts")
     */
    private Collection $feats;

    /**
     * @ORM\ManyToOne(targetEntity=BeastType::class, inversedBy="value")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?BeastType $beastType;

    /**
     * @ORM\ManyToMany(targetEntity=BeastSubType::class, inversedBy="beasts")
     */
    private Collection $subTypes;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $cr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $alignment;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $size;

    /**
     * @ORM\ManyToOne(targetEntity=BeastType::class, inversedBy="beasts")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?BeastType $type = null;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $ac;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $hp;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $hd;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $melee;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $ranged;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $space;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $reach;

    /**
     * @var string|null
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private ?string $image;

    /**
     * @ORM\ManyToMany(targetEntity=Language::class, mappedBy="beasts")
     */
    private Collection $languages;

    /**
     * @ORM\OneToMany(targetEntity=RacialMod::class, mappedBy="beast")
     */
    private Collection $racialMods;

    /**
     * @ORM\ManyToMany(targetEntity=SQ::class, mappedBy="beast")
     */
    private Collection $sQs;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private ?string $speed;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     */
    private ?string $treasure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $groups;

    /**
     * @ORM\ManyToMany(targetEntity=Environment::class, mappedBy="beasts")
     */
    private Collection $environments;

    /**
     * @ORM\ManyToMany(targetEntity=Organization::class, mappedBy="beasts")
     */
    private Collection $organizations;

    /**
     * @ORM\ManyToMany(targetEntity=Gear::class, mappedBy="beasts")
     */
    private Collection $gears;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
        $this->beastStatistiques = new ArrayCollection();
        $this->beastSkills = new ArrayCollection();
        $this->feats = new ArrayCollection();
        $this->subTypes = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->racialMods = new ArrayCollection();
        $this->sQs = new ArrayCollection();
        $this->environments = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        $this->gears = new ArrayCollection();
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

    /**
     * @return string|null
     */
    public function getImage(
    ): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(
        ?string $image
    ): void {
        $this->image = $image;
    }

    /**
     * @return Collection|Language[]
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
            $language->addBeast($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language): self
    {
        if ($this->languages->removeElement($language)) {
            $language->removeBeast($this);
        }

        return $this;
    }

    /**
     * @return Collection|RacialMod[]
     */
    public function getRacialMods(): Collection
    {
        return $this->racialMods;
    }

    public function addRacialMod(RacialMod $racialMod): self
    {
        if (!$this->racialMods->contains($racialMod)) {
            $this->racialMods[] = $racialMod;
            $racialMod->setBeast($this);
        }

        return $this;
    }

    public function removeRacialMod(RacialMod $racialMod): self
    {
        if ($this->racialMods->removeElement($racialMod)) {
            // set the owning side to null (unless already changed)
            if ($racialMod->getBeast() === $this) {
                $racialMod->setBeast(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SQ[]
     */
    public function getSQs(): Collection
    {
        return $this->sQs;
    }

    public function addSQ(SQ $sQ): self
    {
        if (!$this->sQs->contains($sQ)) {
            $this->sQs[] = $sQ;
            $sQ->addBeast($this);
        }

        return $this;
    }

    public function removeSQ(SQ $sQ): self
    {
        if ($this->sQs->removeElement($sQ)) {
            $sQ->removeBeast($this);
        }

        return $this;
    }

    public function getSpeed(): ?string
    {
        return $this->speed;
    }

    public function setSpeed(string $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    public function setEnvironment(string $environment): self
    {
        $this->environment = $environment;

        return $this;
    }

    public function getTreasure(): ?string
    {
        return $this->treasure;
    }

    public function setTreasure(string $treasure): self
    {
        $this->treasure = $treasure;

        return $this;
    }

    public function getGroups(): ?string
    {
        return $this->groups;
    }

    public function setGroups(string $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return Collection|Environment[]
     */
    public function getEnvironments(): Collection
    {
        return $this->environments;
    }

    public function addEnvironment(Environment $environment): self
    {
        if (!$this->environments->contains($environment)) {
            $this->environments[] = $environment;
            $environment->addBeast($this);
        }

        return $this;
    }

    public function removeEnvironment(Environment $environment): self
    {
        if ($this->environments->removeElement($environment)) {
            $environment->removeBeast($this);
        }

        return $this;
    }

    /**
     * @return Collection|Organization[]
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): self
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations[] = $organization;
            $organization->addManyToMany($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): self
    {
        if ($this->organizations->removeElement($organization)) {
            $organization->removeManyToMany($this);
        }

        return $this;
    }

    /**
     * @return Collection|Gear[]
     */
    public function getGears(): Collection
    {
        return $this->gears;
    }

    public function addGear(Gear $gear): self
    {
        if (!$this->gears->contains($gear)) {
            $this->gears[] = $gear;
            $gear->addBeast($this);
        }

        return $this;
    }

    public function removeGear(Gear $gear): self
    {
        if ($this->gears->removeElement($gear)) {
            $gear->removeBeast($this);
        }

        return $this;
    }
}

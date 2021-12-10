<?php

namespace App\Command;

use App\Entity\Beast;
use App\Entity\BeastSkills;
use App\Entity\BeastStatistique;
use App\Entity\BeastSubType;
use App\Entity\BeastType;
use App\Entity\ClassSpell;
use App\Entity\ClassType;
use App\Entity\Component;
use App\Entity\Environment;
use App\Entity\FeatsBeast;
use App\Entity\Gear;
use App\Entity\Language;
use App\Entity\Organization;
use App\Entity\School;
use App\Entity\Spell;
use App\Entity\SQ;
use App\Entity\SubSchool;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'app:import-data',
    description: 'Add a short description for your command',
)]
class ImportDataCommand extends Command
{
    private $client;

    protected function configure(
    ): void
    {
        $this
            ->addOption("spells")
            ->addOption("bestiary")
            ->addOption("site")
            ->addOption("images");
    }

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CacheInterface $cache,
        string $name = null
    ) {
        parent::__construct($name);
        $this->client = new Client();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input,
            $output);
        $args = $input->getOptions();
        foreach ($args as $arg => $value) {
            $this->exec($arg);
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private function generateBestiaryJson(
        TabularDataReader $records
    ): void {

        $rows = json_decode(file_get_contents(
            dirname(__DIR__,
                2) . "/beast-spell.json"),
            true);

        $rows = $this->cache->get("beasts",
            function (
            ) use
            (
                $records,
                $rows
            ) {
                foreach ($records as $record) {
                    $newBeast = [
                        "name" => $record["Name"],
                        "cr" => $record["CR"],
                        "xp" => $record["XP"],
                        "race" => $record["Race"],
                        "alignment" => $record["Alignment"],
                        "size" => $record["Size"],
                        "type" => $record["Type"],
                        "ac" => $record["AC"],
                        "hp" => $record["HP"],
                        "hd" => $record["HD"],
                        "melee" => $record["Melee"],
                        "ranged" => $record["Ranged"],
                        "space" => $record["Space"],
                        "speed" => $record["Speed"],
                        "reach" => $record["Reach"],
                        "stat" => [
                            "str" => $record["Str"],
                            "dex" => $record["Dex"],
                            "con" => $record["Con"],
                            "int" => $record["Int"],
                            "wis" => $record["Wis"],
                            "cha" => $record["Cha"],
                        ],
                        "racialMods" => explode(",",
                            $record["RacialMods"]),
                        "language" => explode(",",
                            $record["Languages"]),
                        "sq" => explode(",",
                            $record["SQ"]),
                        "environment" => explode(",",
                            $record["Environment"]),
                        "organization" => explode(",",
                            $record["Organization"]),
                        "treasure" => $record["Treasure"],
                        "group" => $record["Group"],
                        "gear" => explode(",",
                            $record["Gear"]),
                        "feats" => explode(",",
                            $record["Feats"]),
                        "skills" => explode(",",
                            $record["Skills"]),
                        "subtype" => [],
                    ];

                    $rows[$record["Name"]] = key_exists($record["Name"],
                        $rows) ? array_merge($rows[$record["Name"]],
                        $newBeast) : $newBeast;

                    for ($i = 1; $i <= 6; $i++) {
                        if (key_exists("subtype$i",
                                $record) && !empty($record["subtype$i"])) {
                            $rows[$record["Name"]]["subtype"][] = $record["subtype$i"];
                        }
                    }
                }

                return $rows;
            });



        $i = 0;

        foreach ($rows as $index => $row) {
            if (empty($index)) {
                continue;
            }

            #region init object
            $index = trim($index);
            $beast = $this->entityManager->getRepository(Beast::class)->findOneBy(["name" => $index]);

            $xp = $beast?->getXp() ?? (key_exists("xp",
                    $row) ? floatval($row["xp"]) : 0);
            $description = $beast?->getDescription() ?? (key_exists("description",
                    $row) ? $row["description"] : "");
            $melee = $beast?->getMelee() ?? (key_exists("melee",
                    $row) ? $row["melee"] : "");
            $name = $beast?->getName() ?? $row["name"];

            if (!$beast) {
                $beast = (new Beast());
            }

            $beast->setName($name)
                ->setXp($xp)
                ->setDescription($description)
                ->setMelee($melee)
                ->setHp(key_exists("hp",
                    $row) ? intval($row["hp"]) : 0)
                ->setHd(key_exists("hd",
                    $row) ? intval($row["hd"]) : 0)
                ->setCr(key_exists("cr",
                    $row) ? intval($row["cr"]) : 0)
                ->setAc(key_exists("ac",
                    $row) ? intval($row["ac"]) : 0)
                ->setSpace($row["space"] ?? "")
                ->setSize($row["size"] ?? "")
                ->setSpeed($row["speed"] ?? 0)
                ->setTreasure($row["treasure"] ?? "")
                ->setGroups($row["group"] ?? "")
                ->setReach($row["reach"] ?? "")
                ->setRanged($row["ranged"] ?? "")
                ->setAlignment($row["alignment"] ?? "");
#endregion
            if (key_exists("stat",
                    $row) && $beast->getBeastStatistiques()->count() == 0) {
                foreach ($row["stat"] as $key => $stats) {
                    $stat = (new BeastStatistique())->setName($key)->setValue(intval($stats))->setBeast($beast);
                    $this->entityManager->persist($stat);
                }
            }
            if (key_exists("feats",
                    $row) && $beast->getFeats()->count() == 0) {
                foreach ($row["feats"] as $feats) {
                    $feat = $this->entityManager->getRepository(FeatsBeast::class)->findOneBy(["value" => $feats]);
                    if (!$feat) {
                        $feat = (new FeatsBeast())->setValue($feats);
                    }
                    $feat->addBeast($beast);
                    $this->entityManager->persist($feat);
                }
            }
            if (key_exists("type",
                    $row) && !$beast->getType()) {
                $types = explode(",",
                    $row["type"]);
                foreach ($types as $typeName) {
                    $type = $this->entityManager->getRepository(BeastType::class)->findOneBy(["value" => $typeName]);
                    if (!$type) {
                        $type = (new BeastType())->setValue($typeName);
                    }
                    $type->addBeast($beast);
                    $this->entityManager->persist($type);
                }
            }
            if (key_exists("skills",
                    $row) && $beast->getBeastSkills()->count() == 0) {
                foreach ($row["skills"] as $skills) {
                    $skill = (new BeastSkills())->setValue($skills)->setBeast($beast);
                    $this->entityManager->persist($skill);
                }
            }
            if (key_exists("spell",
                    $row) && $beast->getSpells()->count() == 0) {
                foreach ($row["spell"] as $spells) {
                    $spell = $this->entityManager->getRepository(Spell::class)->findOneBy(["name" => $spells]);
                    if (!$spell) {
                        $spell = $this->entityManager->getRepository(Spell::class)->createQueryBuilder("s")->where("LOWER(s.name) like LOWER(:name)")->setParameter("name",
                            "$spells%")->getQuery()->getResult();
                        if (!$spell) {
                            continue;
                        }
                        $spell = $spell[0];
                    }
                    $spell->addBeast($beast);
                    $this->entityManager->persist($spell);
                }
            }
            if (key_exists("subtype",
                    $row) && $beast->getSubTypes()->count() == 0) {
                foreach ($row["subtype"] as $subtypes) {
                    $sub = $this->entityManager->getRepository(BeastSubType::class)->findOneBy(["name" => $subtypes]);
                    if (!$sub) {
                        $sub = (new BeastSubType())->setName($subtypes);
                    }
                    $sub->addBeast($beast);
                    $this->entityManager->persist($sub);
                }
            }
            if (key_exists("language",
                    $row) && $beast->getLanguages()->count() == 0) {
                foreach ($row["language"] as $language) {
                    $lang = $this->entityManager->getRepository(Language::class)->findOneBy(["name" => $language]);
                    if (!$lang) {
                        $lang = (new Language())->setName($language);
                    }
                    $lang->addBeast($beast);
                    $this->entityManager->persist($lang);
                }
            }
            if (key_exists("gear",
                    $row) && $beast->getGears()->count() == 0) {
                foreach ($row["gear"] as $gear) {
                    $g = $this->entityManager->getRepository(Gear::class)->findOneBy(["value" => $gear]);
                    if (!$g) {
                        $g = (new Gear())->setValue($gear);
                    }
                    $g->addBeast($beast);
                    $this->entityManager->persist($g);
                }
            }
            if (key_exists("environment",
                    $row) && $beast->getEnvironments()->count() == 0) {
                foreach ($row["environment"] as $environment) {
                    $env = $this->entityManager->getRepository(Environment::class)->findOneBy(["name" => $environment]);
                    if (!$env) {
                        $env = (new Environment())->setName($environment);
                    }
                    $env->addBeast($beast);
                    $this->entityManager->persist($env);
                }
            }
            if (key_exists("organization",
                    $row) && $beast->getOrganizations()->count() == 0) {
                foreach ($row["organization"] as $orga) {
                    $organization = $this->entityManager->getRepository(Organization::class)->findOneBy(["name" => $orga]);
                    if (!$organization) {
                        $organization = (new Organization())->setName($orga);
                    }
                    $organization->addBeast($beast);
                    $this->entityManager->persist($organization);
                }
            }
            if (key_exists("sq",
                    $row) && $beast->getSQs()->count() == 0) {
                foreach ($row["sq"] as $sqs) {
                    $sq = $this->entityManager->getRepository(SQ::class)->findOneBy(["name" => $sqs]);
                    if (!$sq) {
                        $sq = (new SQ())->setName($sqs);
                    }
                    $sq->addBeast($beast);
                    $this->entityManager->persist($sq);
                }
            }
            if (key_exists("racialMods",
                    $row) && $beast->getRacialMods()->count() == 0) {
                foreach ($row["racialMods"] as $mod) {
                    $regex = "/([\+|\-]\d)\s(\w+.*)+/";
                    preg_match_all($regex,
                        $mod,
                        $matches);
                }
            }
            $this->entityManager->persist($beast);
            if ($i % 50) {
                $this->entityManager->flush();
            }
            $i++;
        }
    }

    private function generateSpellsJson(
        TabularDataReader $records
    ): void {
        $rows = [];
        foreach ($records as $record) {
            $rows[] = [
                "name" => $record["name"],
                "school" => $record["school"],
                "subschool" => $record["subschool"],
                "descriptor" => $record["descriptor"],
                "spell_level" => array_map(function (
                    $class
                ) {
                    return explode(" ",
                        trim($class));
                },
                    explode(',',
                        $record["spell_level"])),
                "casting_time" => $record["casting_time"],
                "components" => $record["components"],
                "costly_components" => $record["costly_components"],
                "range" => $record["range"],
                "area" => $record["area"],
                "effect" => $record["effect"],
                "targets" => $record["targets"],
                "duration" => $record["duration"],
                "dismissible" => $record["dismissible"],
                "shapeable" => $record["shapeable"],
                "saving_throw" => explode(";",
                    $record["saving_throw"])[0],
                "spell_resistance" => explode(";",
                    $record["spell_resistance"])[0],
                "description" => $record["description"],
                "short_description" => $record["short_description"],
                "divine_focus" => $record["divine_focus"],
                "focus" => $record["focus"],
                "domain" => $record["domain"]
            ];
        }

        $schools = [];
        $subSchools = [];
        $class = [];

        foreach ($rows as $key => $spell) {
            if (!key_exists($spell["school"],
                $schools)) {
                $school = $this->entityManager->getRepository(School::class)->findOneBy(["name" => $spell["school"]]);
                if (!$school) {
                    $school = (new School())->setName($spell["school"]);
                    $this->entityManager->persist($school);
                }
                $schools[$spell["school"]] = $school;
            }
            if (!key_exists($spell["subschool"],
                $subSchools)) {
                $subSchool = $this->entityManager->getRepository(SubSchool::class)->findOneBy(["name" => $spell["subschool"]]);
                if (!$subSchool) {
                    $subSchool = (new SubSchool())->setName($spell["subschool"]);
                    $this->entityManager->persist($subSchool);
                }
                $subSchools[$spell["subschool"]] = $subSchool;
            }

            $newSpell = $this->entityManager->getRepository(Spell::class)->findOneBy(["name" => $spell["name"]]);
            if (!$newSpell) {
                $newSpell = (new Spell())->setDocuments(["data" => ""]);
            }
            $newSpell
                ->setName($spell["name"])
                ->setArea($spell["area"])
                ->setSchool($schools[$spell["school"]])
                ->setSubSchool($subSchools[$spell["subschool"]])
                ->setDesciptor($spell["descriptor"])
                ->setCastingTime($spell["casting_time"])
                ->setCostlyComponents($spell["costly_components"])
                ->setRange($spell["range"])
                ->setEffect($spell["effect"])
                ->setTargets($spell["targets"])
                ->setDuration($spell["duration"])
                ->setDismissible($spell["dismissible"])
                ->setShapeable($spell["shapeable"])
                ->setSavingThrow($spell["saving_throw"])
                ->setSpellResistance($spell["spell_resistance"])
                ->setDescription($spell["description"])
                ->setDomain($spell["domain"])
                ->setDescription($spell["description"])
                ->setFocus($spell["focus"] == 1)
                ->setDivineFocus($spell["divine_focus"] == 1)
                ->setShortDescription($spell["short_description"]);

            foreach ($spell["spell_level"] as $spellLevel) {
                if (str_contains("/",
                    $spellLevel[0])) {
                    $classes = explode("/",
                        $spellLevel[0]);

                    foreach ($classes as $className) {
                        if (!key_exists($className,
                            $class)) {
                            $classType = $this->entityManager->getRepository(ClassType::class)->findOneBy(["name" => $className]);
                            if (!$classType) {
                                $classType = (new ClassType())->setName($className);
                                $this->entityManager->persist($classType);
                            }
                            $class[$className] = $classType;
                        }
                        $classSpell = $this->entityManager->getRepository(ClassSpell::class)->findOneBy([
                            "classType" => $class[$className],
                            'level' => intval($spellLevel[1]),
                            'spell' => $newSpell
                        ]);

                        if (!$classSpell) {
                            $classSpell = (new ClassSpell())->setClassType($class[$className])->setLevel(intval($spellLevel[1]))->setSpell($newSpell);
                            $this->entityManager->persist($classSpell);
                        }
                    }
                } else {
                    if (!key_exists($spellLevel[0],
                        $class)) {
                        $classType = $this->entityManager->getRepository(ClassType::class)->findOneBy(["name" => $spellLevel[0]]);
                        if (!$classType) {
                            $classType = (new ClassType())->setName($spellLevel[0]);
                            $this->entityManager->persist($classType);
                        }
                        $class[$spellLevel[0]] = $classType;
                    }

                    $classSpell = $this->entityManager->getRepository(ClassSpell::class)->findOneBy([
                        "classType" => $class[$spellLevel[0]],
                        'level' => intval($spellLevel[1]),
                        'spell' => $newSpell
                    ]);

                    if (!$classSpell) {
                        $classSpell = (new ClassSpell())->setClassType($class[$spellLevel[0]])->setLevel(intval($spellLevel[1]))->setSpell($newSpell);
                        $this->entityManager->persist($classSpell);
                    }
                    $classSpell = (new ClassSpell())->setClassType($class[$spellLevel[0]])->setLevel(intval($spellLevel[1]))->setSpell($newSpell);
                    $this->entityManager->persist($classSpell);
                }
            }

            $re = '/([A-Z]{1},)|(\s[A-Z]{1}\s\((\s?\w+[\s|,|\)])+\)?)/m';
            preg_match_all($re,
                $spell["components"],
                $matches,
                PREG_SET_ORDER,
                0);
            foreach ($matches as $components) {
                if (mb_strpos($components[0],
                        "(") !== false) {
                    $string = explode(" ",
                        trim($components[0]),
                        2);
                } else {
                    $string = explode(",",
                        $components[0]);
                }

                $component = $this->entityManager->getRepository(Component::class)->findOneBy([
                    "value" => $string[0],
                    "description" => $string[1] ?? ""
                ]);
                if (!$component) {
                    $component = (new Component())->setValue($string[0])->setDescription($string[1] ?? "");
                    $this->entityManager->persist($component);
                }
                $component->setSpell($newSpell);
            }

            $this->entityManager->persist($newSpell);

            if ($key % 50 === 0) {
                $this->entityManager->flush();
            }
        }
    }

    private function scrapeWebSite(
    )
    {
        $firstUrl = "https://www.d20pfsrd.com/bestiary/bestiary-by-challenge-rating";

        $crawler = $this->client->request("GET",
            $firstUrl);

        $links = $crawler->filter(".article-content a");

        $beasts = [];
        foreach ($links as $link) {
            foreach ($link->attributes->getIterator() as $attr) {
                if ($attr->nodeName == "href") {
                    $href = $attr->value;
                    $page = $this->client->request("GET",
                        $href);
                    $liste = $page->filter(".divboxes a");


                    foreach ($liste as $bestLink) {
                        foreach ($bestLink->attributes->getIterator() as $linkAttr) {
                            if ($linkAttr->nodeName == "href") {
                                $href = $linkAttr->value;
                                $beast = $this->scrapeBeast($href);
                                $beasts[$beast["name"]] = $beast;
                            }
                        }
                    }
                }
            }
        }

        file_put_contents(dirname(__DIR__,
                2) . "/beast-spell.json",
            json_encode($beasts));
    }

    private function scrapeBeast(
        string $href
    ) {
        $crawler = $this->client->request("GET",
            $href);

        $links = $crawler->filter(".article-content a");
        $titleNode = $crawler->filter(".article-content .title");
        $contents = $crawler->filter(".article-content > p");

        $beast = [
            "name" => "",
            "spell" => [],
            "description" => []
        ];
        foreach ($titleNode as $node) {
            $beast["name"] = explode(" ",
                $node->textContent)[0];
        }

        foreach ($links as $link) {
            /** @var \DOMElement $attr */
            foreach ($link->attributes as $attr) {
                if ($attr->nodeName == "href") {
                    if (str_contains($attr->value,
                        "magic/all-spells")) {
                        $beast["spell"][] = $link->nodeValue;
                    }
                }
            }
        }

        /** @var \DOMElement $content */
        foreach (array_reverse($contents->getIterator()->getArrayCopy()) as $content) {
            $value = $content->childNodes->item(0);

            if (!is_null($value) && $value->nodeName === "#text") {
                array_unshift($beast["description"],
                    $value->nodeValue);
            } else {
                break;
            }
        }

        $beast["description"] = implode("</br>",
            $beast["description"]);

        return $beast;
    }

    private function insertImage(
    )
    {
        $finder = new Finder();
        $finder->files()->in(dirname(__DIR__,
                2) . "/public/images");

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $fileNameWithExtension = $file->getRelativePathname();
                $name = explode(".",
                    $fileNameWithExtension)[0];
                /** @var Spell[] $spell */
                $spell = $this->entityManager
                    ->getRepository(Spell::class)
                    ->createQueryBuilder("s")
                    ->where("lower(s.name) = :name")
                    ->setParameters(["name" => $name])
                    ->getQuery()->getResult();

                if (!empty($spell)) {
                    $spell[0]->setImage($fileNameWithExtension);
                } else {
                    /** @var Beast[] $beast */
                    $beast = $this->entityManager->getRepository(Beast::class)->createQueryBuilder("b")
                        ->where("lower(b.name) = :name")
                        ->setParameter("name",
                            $name)
                        ->getQuery()->getResult();

                    if (!empty($beast)) {
                        $beast[0]->setImage($fileNameWithExtension);
                    }
                }
            }
            $this->entityManager->flush();
        }
    }

    private function exec(
        mixed $arg
    ) {
        switch ($arg) {
            case "spells":
                $records = $this->getFile("spells");
                $this->generateSpellsJson($records);
                break;
            case "bestiary":
                $records = $this->getFile("bestiary");
                $this->generateBestiaryJson($records);
                break;
            case "site":
                $this->scrapeWebSite();
                break;
            case "images":
                $this->insertImage();
                break;
        }
    }

    private function getFile(
        string $arg
    ) {

        $file = dirname(__DIR__,
                2) . "/var/import/$arg.csv";

        $csv = Reader::createFromPath($file,
            'r');
        $csv->setHeaderOffset(0); //set the CSV header offset

//get 25 records starting from the 11th row
        $stmt = Statement::create();

        $records = $stmt->process($csv);

        return $records;
    }
}

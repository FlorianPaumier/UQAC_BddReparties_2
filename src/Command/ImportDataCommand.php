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
use App\Entity\FeatsBeast;
use App\Entity\School;
use App\Entity\Spell;
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
            ->addArgument('file',
                InputArgument::REQUIRED,
                'Type of files');
    }

    public function __construct(
        string $name = null,
        private EntityManagerInterface $entityManager
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
        $arg1 = $input->getArgument('file');

        if ($arg1) {
            $file = dirname(__DIR__,
                    2) . "/var/import/$arg1.csv";
        }

        if ($arg1 !== "site" && $arg1 !== "images") {

            $csv = Reader::createFromPath($file,
                'r');
            $csv->setHeaderOffset(0); //set the CSV header offset

//get 25 records starting from the 11th row
            $stmt = Statement::create();

            $records = $stmt->process($csv);
        }

        $rows = [];

        switch ($arg1) {
            case "spells":
                $this->generateSpellsJson($records);
                break;
            case "bestiary":
                $this->generateBestiaryJson($records);
                break;
            case "site":
                $this->scrapeWebSite();
                break;
            case "images":
                $this->insertImage();
                break;
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
                "reach" => $record["Reach"],
                "stat" => [
                    "str" => $record["Str"],
                    "dex" => $record["Dex"],
                    "con" => $record["Con"],
                    "int" => $record["Int"],
                    "wis" => $record["Wis"],
                    "cha" => $record["Cha"],
                ],
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

        $i = 0;

        foreach ($rows as $index => $row) {
            if (empty($index)) continue;

            $beast = $this->entityManager->getRepository(Beast::class)->findOneBy(["name" => $index]);

            $xp = $beast?->getXp() ?? (key_exists("xp",
                    $row) ? floatval($row["xp"]) : 0);
            $description = $beast?->getDescription() ?? (key_exists("description",
                        $row) ? $row["description"] : "");
            $melee = $beast?->getMelee() ?? (key_exists("melee",
                    $row) ? $row["melee"] : "");
            $name = $beast?->getName() ?? $row["name"];

            $beast = (new Beast())
                ->setName($name)
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
                ->setReach($row["reach"] ?? "")
                ->setRanged($row["ranged"] ?? "")
                ->setAlignment($row["alignment"] ?? "");

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
                $types = explode(",", $row["type"]);
                foreach ($types as $typeName){
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
                "saving_throw" => $record["saving_throw"],
                "spell_resistance" => $record["spell_resistance"],
                "description" => $record["description"],
                "short_description" => $record["short_description"]
            ];
        }

        $schools = [];
        $subSchools = [];
        $class = [];

        foreach ($rows as $key => $spell) {
            if (!key_exists($spell["school"],
                $schools)) {
                $school = (new School())->setName($spell["school"]);
                $schools[$spell["school"]] = $school;
                $this->entityManager->persist($school);
            }
            if (!key_exists($spell["subschool"],
                $subSchools)) {
                $subSchool = (new SubSchool())->setName($spell["subschool"]);
                $subSchools[$spell["subschool"]] = $subSchool;
                $this->entityManager->persist($subSchool);
            }

            $newSpell = (new Spell())
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
                ->setShortDescription($spell["short_description"]);

            foreach ($spell["spell_level"] as $spellLevel) {
                if (str_contains("/",
                    $spellLevel[0])) {
                    $classes = explode("/",
                        $spellLevel[0]);

                    foreach ($classes as $className) {
                        if (!key_exists($className,
                            $class)) {
                            $classType = (new ClassType())->setName($className);
                            $class[$className] = $classType;
                            $this->entityManager->persist($classType);
                        }
                        $classSpell = (new ClassSpell())->setClassType($class[$className])->setLevel(intval($spellLevel[1]))->setSpell($newSpell);
                        $this->entityManager->persist($classSpell);
                    }
                } else {
                    if (!key_exists($spellLevel[0],
                        $class)) {
                        $classType = (new ClassType())->setName($spellLevel[0]);
                        $class[$spellLevel[0]] = $classType;
                        $this->entityManager->persist($class[$spellLevel[0]]);
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

                $component = (new Component())->setValue($string[0])->setDescription($string[1] ?? "");
                $component->setSpell($newSpell);
                $this->entityManager->persist($component);
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
                $name = explode(".", $fileNameWithExtension)[0];
                /** @var Spell[] $spell */
                $spell = $this->entityManager
                    ->getRepository(Spell::class)
                    ->createQueryBuilder("s")
                    ->where("lower(s.name) = :name")
                    ->setParameters(["name" => $name])
                    ->getQuery()->getResult();

                if (count($spell) > 0) {
                    $spell[0]->setImage($fileNameWithExtension);
                }
            }
            $this->entityManager->flush();
        }
    }
}

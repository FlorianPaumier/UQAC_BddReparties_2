<?php

namespace App\Controller;

use App\Repository\BeastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route(path:'/', name: 'app_home', methods: ["GET"])]
    public function home(): Response {
        return $this->render("home.html.twig");
    }

    #[Route("/json")]
    public function BeastJson(
        BeastRepository $beastRepository
    ) {
        $beasts = $beastRepository->createQueryBuilder("b")
            ->select("b.name as beast", "s.name as spell")
            ->innerJoin("b.spells", "s")
            ->getQuery()->getArrayResult();

        $json = [];
        foreach ($beasts as $beast){
            if (!key_exists($beast["beast"], $json)) {
                $json[$beast["beast"]] = [];
            }

            if (!in_array($beast["spell"], $json[$beast["beast"]])) {
                $json[$beast["beast"]][] = $beast["spell"];
            }
        }

        file_put_contents("best-spell.json", json_encode($json));
        return new Response();
    }
}

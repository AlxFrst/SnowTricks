<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $tricks): Response
    {
        $criteria = [];
        if ($this->getUser() !== null){
            $criteria = ['publicationStatusTrick' => 'Published' ];
        }

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks->findBy($criteria,['created_at' => 'ASC'],6,0),
            'offset' => 6
        ]);
    }
}

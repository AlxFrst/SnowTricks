<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(TrickRepository $tricks): Response
    {
        $tricks = $tricks->findBy([], ['created_at' => 'DESC'], 6);
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}

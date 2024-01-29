<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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

    #[Route('/nextTricks/{offset}', name: 'app_loadMore')]
    public function loadMoreTricks(TrickRepository $tricks, $offset): Response
    {
        $criteria = [];
        if ($this->getUser() !== null){
            $criteria = ['publicationStatusTrick' => 'Published' ];
        }
        return $this->render('main/_trickList_partial.html.twig', [
            'tricks' => $tricks->findBy($criteria,['created_at' => 'ASC'],8,$offset)
        ]);
    }
}

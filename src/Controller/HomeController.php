<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const INITIAL_TRICKS_LIMIT = 6;
    private const ADDITIONAL_TRICKS_LIMIT = 8;

    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $trickRepository): Response
    {
        $criteria = $this->getTrickCriteria();

        return $this->render('home/index.html.twig', [
            'tricks' => $trickRepository->findBy($criteria, ['created_at' => 'ASC'], self::INITIAL_TRICKS_LIMIT),
            'offset' => self::INITIAL_TRICKS_LIMIT
        ]);
    }

    #[Route('/nextTricks/{offset}', name: 'app_loadMore')]
    public function loadMoreTricks(TrickRepository $trickRepository, int $offset): Response
    {
        $criteria = $this->getTrickCriteria();

        return $this->render('home/_trickList_partial.html.twig', [
            'tricks' => $trickRepository->findBy($criteria, ['created_at' => 'ASC'], self::ADDITIONAL_TRICKS_LIMIT, $offset)
        ]);
    }

    private function getTrickCriteria(): array
    {
        if ($this->getUser() !== null) {
            return ['publicationStatusTrick' => 'Published'];
        }

        return [];
    }
}

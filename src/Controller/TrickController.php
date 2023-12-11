<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\PostType;
use App\Form\TrickType;
use App\Repository\PostRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/trick')]
class TrickController extends AbstractController
{
    #[Route('/', name: 'app_trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    #[Route('/{slug}', name: 'app_trick', methods: ['GET', 'POST'])]
    public function read(Trick $trick, PostRepository $postRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null === $this->getUser()) {
                $this->addFlash('error', 'You need to be login to write a comment');
                return new RedirectResponse($request->headers->get('referer'));

            }
            /** @var User $user */
            $user = $this->getUser();
            $post->setUser($user)
                ->setTrick($trick);
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'Your comment has been published.');
            return $this->redirectToRoute('app_trick', ['slug' => $trick->getSlug()]);
        }

        $posts = $postRepository->findBy(['trick' => $trick], ['created_at' => 'DESC'], 5, 0);

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'posts' => $posts,
            'form' => $form->createView(),
            'offsetpost' => 5
        ]);
    }
}

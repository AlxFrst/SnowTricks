<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\PostType;
use App\Form\TrickType;
use App\Repository\PostRepository;
use App\Repository\TrickRepository;
use App\Service\FilterYoutubeUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    /**
     * @param TrickRepository $trickRepository
     * @return Response
     */
    #[Route('/', name: 'app_trick_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    #[Route('/all', name: 'app_trick_all', methods: ['GET'])]
    public function all(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/all.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, TrickRepository $trickRepository, SluggerInterface $slugger, FilterYoutubeUrlService $filterVideoLink): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un trick avec le même nom existe déjà
            $existingTrick = $trickRepository->findOneBy(['name' => $trick->getName()]);

            if ($existingTrick) {
                // Si un trick avec le même nom existe déjà, informer l'utilisateur
                $this->addFlash('error', 'Un trick avec ce nom existe déjà. Veuillez choisir un nom différent.');
                return $this->redirectToRoute('app_trick_new', [], Response::HTTP_SEE_OTHER);
            }

            $pictureCollectionFields = $form->get('pictures');
            $hasValidPicture = false;

            foreach ($pictureCollectionFields as $pictureField) {
                $picture = $pictureField->getData();
                if (null !== $picture && null !== $picture->getFile()) {
                    $hasValidPicture = true;
                    $pictureFile = $picture->getFile();
                    $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                    try {
                        $pictureFile->move(
                            $this->getParameter('Pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Problème lors du téléchargement de l\'image.');
                        return $this->redirectToRoute('app_trick_new', [], Response::HTTP_SEE_OTHER);
                    }

                    $picture->setPictureLink($newFilename);
                    $picture->setTrick($trick);
                    $picture->setUser($this->getUser());
                }
            }

            if (!$hasValidPicture) {
                $this->addFlash('error', 'Au moins une image doit être fournie pour la figure.');
                return $this->redirectToRoute('app_trick_new', [], Response::HTTP_SEE_OTHER);
            }

            // La logique pour traiter les vidéos reste inchangée...

            /** @var User $user */
            $user = $this->getUser();
            $trick->setSlug($slugger->slug($trick->getName()));
            $trick->setUser($user);

            $trickRepository->add($trick, true);
            $this->addFlash('success', "Votre figure a bien été ajoutée");
            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }


    /**
     * @param Trick $trick
     * @param PostRepository $postRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{slug}', name: 'app_trick', methods: ['GET', 'POST'])]
    public function read(Trick $trick, PostRepository $postRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null === $this->getUser()) {
                $this->addFlash('error', 'Vous devez être connecté pour poster un commentaire');
                return new RedirectResponse($request->headers->get('referer'));

            }
            /** @var User $user */
            $user = $this->getUser();
            $post->setUser($user)
                ->setTrick($trick);
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire a bien été ajouté');
            // redirect to trick page
            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        $posts = $postRepository->findBy(['trick' => $trick], ['created_at' => 'DESC'], 5, 0);

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'posts' => $posts,
            'form' => $form->createView(),
            'offsetpost' => 5
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Trick $trick, TrickRepository $trickRepository, SluggerInterface $slugger, FilterYoutubeUrlService $filterVideoLink, LoggerInterface $logger): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureCollectionFields = $form->get('pictures');

            foreach ($pictureCollectionFields as $pictureField) {
                if ($pictureField->getData()->getFile() != null) {
                    $picture = $pictureField->getData();
                    $pictureFile = $picture->getFile();
                    $originalFilename = pathinfo($pictureFile, PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                    try {
                        $pictureFile->move(
                            $this->getParameter('Pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $logger->error($e->getMessage());
                        $this->addFlash('error', "Impossible d'ajouter l'image");
                        return $this->redirectToRoute('app_trick_edit', [], Response::HTTP_SEE_OTHER);
                    }

                    $picture->setPictureLink($newFilename);
                    $picture->setTrick($trick);
                    /** @var User $user */
                    $user = $this->getUser();
                    $picture->setUser($user);
                }
            }

            $videoCollectionFields = $form->get('video');
            foreach ($videoCollectionFields as $videoField) {
                $video = $videoField->getData();
                $videoLink = $video->getVideoLink();
                try {
                    $video->setVideoLink($filterVideoLink->filterVideoLink($videoLink));
                } catch (\RuntimeException $e) {
                    $this->addFlash("error", $e->getMessage());
                    return new RedirectResponse($request->headers->get('referer'));
                }

                $video->setUser($this->getUser());
            }

            $trickRepository->add($trick, true);
            $this->addFlash(
                'notice',
                "Votre figure a bien été modifiée"
            );
            return $this->redirectToRoute('app_trick', ['slug' => $trick->getSlug()]);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/status/{publicationStatus}', name: 'app_trick_status', methods: ['GET', 'POST'])]
    public function updateStatus(Request $request, string $publicationStatus, Trick $trick, TrickRepository $trickRepository): Response
    {
        if ($publicationStatus == 'Unpublished') {
            $message = 'La figure a été dépubliée';
        } else {
            $message = 'La figure a été publiée';
        }

        $this->addFlash(
            'notice',
            $message
        );
        $trick->setPublicationStatusTrick($publicationStatus);
        $trickRepository->add($trick, true);
        return new RedirectResponse($request->headers->get('referer'));

    }

    /**
     * @param PostRepository $postRepository
     * @param Trick $trick
     * @param int $offsetPost
     * @return Response
     */
    #[Route('/{slug}/nextposts/{offsetPost}', name: 'app_post_loadMore', methods: ['GET', 'POST'])]
    public function loadMorePosts(PostRepository $postRepository, Trick $trick, int $offsetPost): Response
    {

        $posts = $postRepository->findBy(['trick' => $trick], ['created_at' => 'DESC'], 5, $offsetPost);

        return $this->render('trick/_postList.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/{slug}/delete', name: 'app_trick_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $trickRepository->remove($trick, true);
            $this->addFlash('success', "La figure a bien été supprimée");
        }

        return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
    }
}
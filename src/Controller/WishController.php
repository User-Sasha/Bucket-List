<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\UserRepository;
use App\Repository\WishRepository;
use App\Services\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/wish', name: 'wish', methods: 'GET')] // Prefixe
class WishController extends AbstractController
{

    #[Route(
        '/supprimer/{wish}',
        name: '_supprimer',
        methods: 'GET'
    )]
    public function supprimer(
        Wish $wish,
        EntityManagerInterface $entityManager
    ): Response
    {
        $entityManager->remove($wish);
        $entityManager->flush();
        return $this->redirectToRoute('wish_list');
    }

    #[IsGranted('ROLE_USER')]
    #[Route(
        '/list',
        name: '_list',
        methods: 'GET'
    )]
    public function list(
        WishRepository $wishRepository,
        UserRepository $userRepository
    ): Response
    {
        //Methode 1
        $wishes = $wishRepository->findBy(
            [
                "isPublished" => true,
                "author" => $this->getUser()
            ]
        );
        return $this->render('wish/list.html.twig',
            compact('wishes')
        );
    }
    #[Route(
        '/detail/{wish}',
        name: '_detail',
        methods: 'GET'
    )]
    public function detail(
        Wish $wish
    ): Response
    {
        if (!$wish) {
            throw $this->createNotFoundException('Ce wish n\'existe pas.');
        }
        return $this->render('wish/detail.html.twig',
        compact('wish')
        );
    }

    #[IsGranted('ROLE_USER')]
    #[Route(
        '/ajouter',
        name: '_ajouter',
        methods: ['GET', 'POST']
    )]
    public function ajouter(
        Request                $request,
        EntityManagerInterface $entityManager,
        Censurator $censurator
    ): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            try {
                $wish->setDateCreated(new \DateTime());
                $wish->setIsPublished(true);
                $wish->setAuthor($this->getUser());

                $wish->setTitle($censurator->purify($wish->getTitle()));
                $wish->setDescription($censurator->purify($wish->getDescription()));

                $entityManager->persist($wish);
                $entityManager->flush();
                $this->addFlash('succes','Le souhait a bien été inséré');
                return $this->redirectToRoute('wish_list');

            } catch (\Exception $exception) {
                $this->addFlash('echec','Le souhait n\'a pas été inséré');
                return $this->redirectToRoute('wish_ajouter');
            }
        }

        return $this->render('ajouter.html.twig',
            compact('wishForm')
        );
    }

    #[IsGranted('ROLE_USER')]
    #[Route(
        '/modifier/{wish}',
        name: '_modifier',
        methods: 'GET'
    )]
    public function modifier(
        Wish $wish,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if($wishForm->isSubmitted() && $wishForm->isValid()) {
            $entityManager->persist($wish);
            $entityManager->flush();
            return $this->redirectToRoute('wish_list');
        }
        return $this->render('wish/modifier.html.twig',
            compact('wishForm')
        );
    }
}

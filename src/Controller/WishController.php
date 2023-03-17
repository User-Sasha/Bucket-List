<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/wish', name: 'wish', methods: 'GET')] // Prefixe
class WishController extends AbstractController
{

    #[Route(
        '/supprimer/{id}',
        name: '_supprimer',
        methods: 'GET'
    )]
    public function supprimer(
        Wish $id,
        EntityManagerInterface $entityManager
    ): Response
    {
        $entityManager->remove($id);
        $entityManager->flush();
        return $this->redirectToRoute('wish_List');
    }

    #[Route(
        '/list',
        name: '_List',
        methods: 'GET'
    )]
    public function List(
        WishRepository $wishRepository
    ): Response
    {
        $wishes = $wishRepository->findBy(
            ["isPublished" => true]
        );
        return $this->render('wish/list.html.twig',
            compact('wishes')
        );
    }
    #[Route(
        '/detail/{wish}',
        name: '_Detail',
        methods: 'GET'
    )]
    public function Detail(
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

    #[Route(
        '/ajouter',
        name: '_ajouterwish',
        methods: ['GET', 'POST']
    )]
    public function ajouterWish(
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            try {
                $wish->setDateCreated(new \DateTime());
                $wish->setIsPublished(true);
                $entityManager->persist($wish);
                $entityManager->flush();
                $this->addFlash('succes','Le souhait a bien été inséré');
                return $this->redirectToRoute('wish_List');

            } catch (\Exception $exception) {
                $this->addFlash('echec','Le souhait n\'a pas été inséré');
                return $this->redirectToRoute('wish_ajouterwish');
            }
        }

        return $this->render('ajouterwish.html.twig',
            compact('wishForm')
        );
    }
}

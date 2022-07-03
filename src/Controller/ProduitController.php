<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Form\ProduitType;
use App\Services\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/produit')]
class ProduitController extends AbstractController
{
    private $em;
    private $imgService;
    public function __construct(EntityManagerInterface $em, ImageService $imgService)
    {
        $this->em = $em;
        $this->imgService = $imgService;
    }



    //§ =============================== INDEX (Liste des Produits) ================================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/', name: 'produit_index', methods: ['GET'])]
    public function index(): Response
    {
        $produits = $this->em->getRepository(Produit::class)->findAll();

        return $this->render('produit/index.html.twig', compact('produits'));
    }



    //§ ================================ NEW (Ajout d'un Produit) =================================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/new', name: 'produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photo']->getData();

            if ($file) {
                $this->imgService->moveImage($file, $produit);
            }

            $this->em->persist($produit);
            $this->em->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->renderForm('produit/new.html.twig', compact('produit', 'form'));
    }



    //§ ============================== SHOW (Détails d'un Produit) ===============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------


    #[Route('/{id}', name: 'produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {

        return $this->render('produit/show.html.twig', compact('produit'));
    }



    //§ ============================= EDIT (Mise à jour d'un Produit) =============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/{id}/edit', name: 'produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit): Response
    {
        if (is_null($produit)) {

            return $this->redirectToRoute('produit_index');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photo']->getData();

            if ($file) {
                $this->imgService->updImage($file, $produit);
            }

            $this->em->persist($produit);
            $this->em->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->renderForm('produit/edit.html.twig', compact('produit', 'form'));
    }



    //§ ============================ DELETE (Suppression d'un Produit) ============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/{id}', name: 'produit_delete', methods: ['POST'])]
    public function delete(Produit $produit): Response
    {
        if ($produit) {

            if (!is_null($produit->getPhoto())) {
                $this->imgService->delImage($produit);
            }

            $this->em->remove($produit);
            $this->em->flush();
        }

        return $this->redirectToRoute('produit_index');
    }
}

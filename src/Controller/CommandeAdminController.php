<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\CommandeAdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/commande')]
class CommandeAdminController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }



    //§ =============================== INDEX (Liste des Commandes) ===============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/', name: 'commande_index', methods: ['GET'])]
    public function index(): Response
    {
        $commandes = $this->em->getRepository(Commande::class)->findAll();

        return $this->render('commande/index.html.twig', compact('commandes'));
    }



    //§ =============================== NEW (Ajout d'une Commande) ================================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/new', name: 'commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeAdminType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $this->em->getRepository(Produit::class)->find($commande->getIdProduit());
            $commande->setMontant($commande->getQuantite() * $produit->getPrix());

            $this->em->persist($commande);
            $this->em->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/new.html.twig', ['form' => $form->createView()]);
    }



    //§ ============================== SHOW (Détails d'une Commande) ==============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/{id}', name: 'commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', compact('commande'));
    }



    //§ ============================ EDIT (Mise à jour d'un Commande) =============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/{id}/edit', name: 'commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande): Response
    {
        $form = $this->createForm(CommandeAdminType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $this->em->getRepository(Produit::class)->find($commande->getIdProduit());
            $commande->setMontant($commande->getQuantite() * $produit->getPrix());

            $this->em->persist($commande);
            $this->em->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->renderForm('commande/edit.html.twig', compact('commande', 'form'));
    }



    //§ =========================== DELETE (Suppression d'une Commande) ===========================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/{id}', name: 'commande_delete', methods: ['POST'])]
    public function delete(Commande $commande): Response
    {
        if ($commande) {
            if($commande->getEtat() != 'En cours') {
                $produit = $commande->getIdProduit();
                $produit->setStock($produit->getStock() + $commande->getQuantite());
            }

            $this->em->remove($commande);
            $this->em->flush();
        }

        return $this->redirectToRoute('commande_index');
    }
}

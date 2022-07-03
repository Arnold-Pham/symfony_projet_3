<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\CommandeMembreType;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/commande')]
class CommandeMembreController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }



    //§ ========================================= PANIER ==========================================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/panier', name: 'panier')]
    public function panier(): Response
    {
        $commandes = $this->em->getRepository(Commande::class)->findBy(['id_membre' => $this->getUser()->getId(), 'etat' => 'En cours']);
        $total = 0;

        foreach ($commandes as $value) {
            $total += $value->getMontant();
        }

        return $this->render('home/panier.html.twig', compact('commandes', 'total'));
    }



    //§ ============================= NEW (Commande pour les Membres) =============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/new/{id}', name: 'commande_user_new', methods: ['GET', 'POST'])]
    public function new(Produit $produit, Request $request): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeMembreType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $this->em->getRepository(Produit::class)->find($produit);

            if($commande->getQuantite() > $produit->getStock()){
                $this->addFlash('message' , 'Stock trop limité pour votre demande.');

                return $this->render('home/new.html.twig', ['form' => $form->createView(), 'produit' => $produit]);
            }

            $commande->setMontant($commande->getQuantite() * $produit->getPrix());
            $commande->setIdProduit($produit);
            $commande->setIdMembre($this->getUser());
            $commande->setEtat('En cours');

            $this->em->persist($commande);
            $this->em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('home/new.html.twig', ['form' => $form->createView(), 'produit' => $produit]);
    }



    //§ ============================= DEL (Commande pour les Membres) =============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/del/{id}', name: 'commande_user_del', methods: ['GET', 'POST'])]
    public function del(Commande $commande): Response
    {
        if ($commande && $commande->getIdMembre() == $this->getUser()) {
            $this->em->remove($commande);
            $this->em->flush();
        }

        return $this->redirectToRoute('panier');
    }



    //§ ========================== VALIDER (Validation commande Membres) ==========================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/val', name: 'commande_user_val', methods: ['GET', 'POST'])]
    public function val(): Response
    {
        $commandes = $this->em->getRepository(Commande::class)->findBy(['id_membre' => $this->getUser()->getId(), 'etat' => 'En cours']);
        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));

        foreach ($commandes as $value) {
            $commande = $this->em->getRepository(Commande::class)->find($value->getId());
            $commande->setEtat('Envoyé');
            $commande->setDateEnregistrement($date);

            $produit = $this->em->getRepository(Produit::class)->find($commande->getIdProduit());
            $produit->setStock($produit->getStock() - $commande->getQuantite());

            $this->em->persist($commande);
            $this->em->flush();
        }


        return $this->redirectToRoute('commande_user_passe');
    }



    //§ ============================ SHOW (Commandes validés Membres) =============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/passe', name: 'commande_user_passe', methods: ['GET', 'POST'])]
    public function passe(): Response
    {
        $commandes = $this->em->getRepository(Commande::class)->findBy(['id_membre' => $this->getUser()->getId(), 'etat' => 'Envoyé']);

        return $this->render('home/envoye.html.twig', compact('commandes'));
    }
}

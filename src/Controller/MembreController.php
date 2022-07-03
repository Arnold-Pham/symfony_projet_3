<?php

namespace App\Controller;


use App\Entity\Membre;
use App\Form\MembreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/admin/membre')]
class MembreController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }



    //§ ================================ INDEX (Liste des Membres) ================================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/', name: 'membre_index', methods: ['GET'])]
    public function index(): Response
    {
        $membres = $this->em->getRepository(Membre::class)->findAll();

        return $this->render('membre/index.html.twig', compact('membres'));
    }



    //§ ================================= NEW (Ajout d'un Membre) =================================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/new', name: 'membre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $membre = new Membre();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membre->setPassword(
                $userPasswordHasher->hashPassword(
                    $membre,
                    $form->get('password')->getData()
                )
            );

            $membre->setRoles([]);
            $choixStatut = $form->get('statut')->getData();

            if ($choixStatut == 1) {
                $membre->setRoles(["ROLE_ADMIN", "ROLE_MEMBRE"]);
            } elseif ($choixStatut == 0) {
                $membre->setRoles(["ROLE_MEMBRE"]);
            }

            $this->em->persist($membre);
            $this->em->flush();

            return $this->redirectToRoute('membre_index');
        }

        return $this->renderForm('membre/new.html.twig', compact('form'));
    }



    //§ =============================== SHOW (Détails d'un Membre) ================================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/{id}', name: 'membre_show', methods: ['GET'])]
    public function show(Membre $membre): Response
    {
        return $this->render('membre/show.html.twig', compact('membre'));
    }



    //§ ============================= EDIT (Mise à jour d'un Membre) ==============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/{id}/edit', name: 'membre_edit', methods: ['GET', 'POST'])]
    public function edit(Membre $membre, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if (is_null($membre)) {

            return $this->redirectToRoute('membre_index');
        }

        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData() != '') {
                $membre->setPassword(
                    $userPasswordHasher->hashPassword(
                        $membre,
                        $form->get('password')->getData()
                    )
                );
            }

            $membre->setRoles([]);
            $valStatut = $form->get('statut')->getData();

            if ($valStatut == 1) {
                $membre->setRoles(["ROLE_ADMIN", "ROLE_MEMBRE"]);
            } elseif ($valStatut == 0) {
                $membre->setRoles(["ROLE_MEMBRE"]);
            }

            $this->em->persist($membre);
            $this->em->flush();

            return $this->redirectToRoute('membre_index');
        }

        return $this->renderForm('membre/edit.html.twig', compact('membre', 'form'));
    }



    //§ ============================ DELETE (Suppression d'un Membre) =============================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/{id}', name: 'membre_delete', methods: ['POST'])]
    public function delete(Membre $membre): Response
    {
        if ($membre) {
            $this->em->remove($membre);
            $this->em->flush();
        }

        return $this->redirectToRoute('membre_index');
    }
}

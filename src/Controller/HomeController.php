<?php

namespace App\Controller;


use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    
    //§ ======================================== HOME PAGE ========================================
    //* ------------------------------------- [ Fonctionnel ] -------------------------------------

    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository(Produit::class)->findAll();

        return $this->render('home/index.html.twig', compact('articles'));
    }
}

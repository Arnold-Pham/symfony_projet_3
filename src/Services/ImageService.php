<?php

namespace App\Services;


use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ImageService extends AbstractController
{
    public function moveImage(UploadedFile $file, Produit $produit): void
    {
        $dossier_upload = $this->getParameter('upload_directory');
        $photo = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($dossier_upload, $photo);
        $produit->setPhoto($photo);
    }

    public function delImage(Produit $produit): void
    {
        $dossier_upload = $this->getParameter('upload_directory');
        $photo = $produit->getPhoto();
        $oldImg = $dossier_upload . '/' . $photo;

        if (file_exists($oldImg)) {
            unlink($oldImg);
        }
    }

    public function updImage(UploadedFile $file, Produit $produit): void
    {
        $this->delImage($produit);
        $this->moveImage($file, $produit);
    }
}

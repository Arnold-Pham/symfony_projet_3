<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Membre::class, inversedBy: 'commandes')]
    private $id_membre;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'commandes')]
    private $id_produit;

    #[ORM\Column(type: 'integer')]
    #[Assert\GreaterThan(value: 0)]
    private $quantite;

    #[ORM\Column(type: 'float')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    private $montant;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Choice(callback: 'choixEtat')]
    private $etat;

    #[ORM\Column(type: 'datetime')]
    #[Assert\Type('datetime')]
    private $date_enregistrement;


    
    public function __construct()
    {
        $tz = new \DateTimeZone('Europe/Paris');
        $now = new \DateTime();
        $now->setTimezone($tz);
        $this->setDateEnregistrement($now);
    }

    public function choixEtat(): array
    {
        return ['En cours', 'Envoyé', 'Livré'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdMembre(): ?Membre
    {
        return $this->id_membre;
    }

    public function setIdMembre(?Membre $id_membre): self
    {
        $this->id_membre = $id_membre;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->id_produit;
    }

    public function setIdProduit(?Produit $id_produit): self
    {
        $this->id_produit = $id_produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateEnregistrement(): ?\DateTimeInterface
    {
        return $this->date_enregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $date_enregistrement): self
    {
        $this->date_enregistrement = $date_enregistrement;

        return $this;
    }
}

<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(min: 3, max: 255)]
    private $titre;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\CssColor(formats: Assert\CssColor::HEX_LONG)]
    private $couleur;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Choice(callback: 'choixTaille')]
    private $taille;

    #[ORM\Column(type: 'string', length: 1)]
    #[Assert\Choice(callback: 'choixCollection')]
    private $collection;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(min: 3, max: 255)]
    private $photo;

    #[ORM\Column(type: 'float')]
    #[Assert\GreaterThan(value: 0)]
    private $prix;

    #[ORM\Column(type: 'integer')]
    #[Assert\GreaterThanOrEqual(value: 0)]
    private $stock;

    #[ORM\Column(type: 'datetime')]
    #[Assert\Type('datetime')]
    private $date_enregistrement;

    #[ORM\OneToMany(mappedBy: 'id_produit', targetEntity: Commande::class)]
    private $commandes;



    public function __construct()
    {
        $tz = new \DateTimeZone('Europe/Paris');
        $now = new \DateTime();
        $now->setTimezone($tz);
        $this->setDateEnregistrement($now);

        $this->commandes = new ArrayCollection();
    }

    public function choixCollection(): array
    {
        return ['M', 'F'];
    }

    public function choixTaille(): array
    {
        return ['2XS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getTaille(): ?string
    {
        
        return $this->taille;
    }

    public function setTaille(string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function setCollection(string $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

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

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setIdProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getIdProduit() === $this) {
                $commande->setIdProduit(null);
            }
        }

        return $this;
    }
}

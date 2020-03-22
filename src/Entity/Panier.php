<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
class Panier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotNull
     * @Assert\Positive
     */
    private $quantite;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type(type="datetime")
     * @var string A "Y-m-d H:i:s" formatted value
     */
    private $date_ajout;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":"0"})
     * @Assert\Type(type="boolean")
     */
    private $etat = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="panier")
     */
    private $produit;

    public function __construct()
    {
        date_default_timezone_set("Europe/Paris");
        $this->date_ajout = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(\DateTimeInterface $date_ajout): self
    {
        $this->date_ajout = $date_ajout;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\LivrableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivrableRepository::class)
 */
class Livrable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLimit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAfecte;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSoumis;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="livrables")
     */
    private $formateurs;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="livrables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brief;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="livrable")
     */
    private $commentaires;

    public function __construct()
    {
        $this->formateurs = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDateLimit(): ?\DateTimeInterface
    {
        return $this->dateLimit;
    }

    public function setDateLimit(\DateTimeInterface $dateLimit): self
    {
        $this->dateLimit = $dateLimit;

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

    public function getDateAfecte(): ?\DateTimeInterface
    {
        return $this->dateAfecte;
    }

    public function setDateAfecte(\DateTimeInterface $dateAfecte): self
    {
        $this->dateAfecte = $dateAfecte;

        return $this;
    }

    public function getDateSoumis(): ?\DateTimeInterface
    {
        return $this->dateSoumis;
    }

    public function setDateSoumis(\DateTimeInterface $dateSoumis): self
    {
        $this->dateSoumis = $dateSoumis;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
        }

        return $this;
    }

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setLivrable($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getLivrable() === $this) {
                $commentaire->setLivrable(null);
            }
        }

        return $this;
    }
}

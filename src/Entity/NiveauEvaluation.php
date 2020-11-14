<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NiveauEvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=NiveauEvaluationRepository::class)
 */
class NiveauEvaluation
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
     * @ORM\Column(type="string", length=255)
     */
    private $critere_evaluation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $groupe_action;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveau",cascade={"persist"})
     */
    private $competence;

    /**
     * @ORM\ManyToMany(targetEntity=LivrablePartiel::class, mappedBy="niveaux")
     */
    private $livrablePartiels;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="niveauEvaluations")
     */
    private $brief;

    public function __construct()
    {
        $this->livrablePartiels = new ArrayCollection();
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

    public function getCritereEvaluation(): ?string
    {
        return $this->critere_evaluation;
    }

    public function setCritereEvaluation(string $critere_evaluation): self
    {
        $this->critere_evaluation = $critere_evaluation;

        return $this;
    }

    public function getGroupeAction(): ?string
    {
        return $this->groupe_action;
    }

    public function setGroupeAction(string $groupe_action): self
    {
        $this->groupe_action = $groupe_action;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablePartiels(): Collection
    {
        return $this->livrablePartiels;
    }

    public function addLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if (!$this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels[] = $livrablePartiel;
            $livrablePartiel->addNiveau($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels->removeElement($livrablePartiel);
            $livrablePartiel->removeNiveau($this);
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

    
}
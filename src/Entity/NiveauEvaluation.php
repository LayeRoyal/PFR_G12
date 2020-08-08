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
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveau")
     */
    private $competence;

    
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

    
}

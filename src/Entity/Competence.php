<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ApiResource(
 * attributes={"pagination_items_per_page"=3},
 * collectionOperations={
 *          "get"={"path"="/admin/competences"},
 *          "post"={"security"="is_granted('ROLE_ADMIN') ",
 *              "security_message"="Seul l'admin a accès à cette ressource",
 *              "path"="/admin/competences"}
 * },
 * itemOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *              "security_message"="Seul l'admin ou le formateur ou le CM a accès à cette ressource",
 *              "path"="/admin/competences/{id}"},
 *          "put"={"security"="is_granted('ROLE_ADMIN') ",
 *              "security_message"="Seul l'admin a accès à cette ressource",
 *              "path"="/admin/competences/{id}"},
 *          "archivage"={"method"="put","security"="is_granted('ROLE_ADMIN') ",
 *              "security_message"="Seul l'admin a accès à cette ressource",
 *              "path"="/admin/competences/{id}/archivage"}
 * }
 * )
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 */
class Competence
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
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, mappedBy="competence")
     */
    private $groupeCompetences;


    /**
     * @ORM\OneToMany(targetEntity=NiveauEvaluation::class, mappedBy="competence")
     */
    private $niveauEvaluations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveauEvaluations = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            $groupeCompetence->removeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|NiveauEvaluation[]
     */
    public function getNiveauEvaluations(): Collection
    {
        return $this->niveauEvaluations;
    }

    public function addNiveauEvaluation(NiveauEvaluation $niveauEvaluation): self
    {
        if (!$this->niveauEvaluations->contains($niveauEvaluation)) {
            $this->niveauEvaluations[] = $niveauEvaluation;
            $niveauEvaluation->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveauEvaluation(NiveauEvaluation $niveauEvaluation): self
    {
        if ($this->niveauEvaluations->contains($niveauEvaluation)) {
            $this->niveauEvaluations->removeElement($niveauEvaluation);
            // set the owning side to null (unless already changed)
            if ($niveauEvaluation->getCompetence() === $this) {
                $niveauEvaluation->setCompetence(null);
            }
        }

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }

}
<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ApiResource(
 * attributes={"pagination_items_per_page"=3},
 * collectionOperations={
    *         "post"={"security"="is_granted('ROLE_ADMIN')", 
    *          "security_message"="Seul un admin peut faire cette action.",
    *          "path"="/admin/groupecompetencesTeam1"},
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')) ",
    *         "security_message"="Vous n'avez pas acces a cette ressource.",
    *          "path"="/admin/groupecompetencesTeam1",
    *         },
    *     },
 *itemOperations={
    *       "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *       "security_message"="Seul l'admin, le formateur et le CM sont autorisés a cette ressource",
    *       "path"="/admin/grpecompetences/{id}"},
    *       "competencedungroupe"={"method"="get","security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *       "security_message"="Seuls l'admin, le formateur et le CM sont autorisés a cette ressource",
    *       "path"="/admin/grpecompetences/{id}/competences"}, 
    *       "put"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *       "security_message"="Seuls l'admin, le formateur et le CM sont autorisés a cette ressource",
    *       "path"="/admin/grpecompetences/{id}"},  
*}

 * )
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 */
class GroupeCompetence
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
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences")
     */
    private $competence;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
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
     * @return Collection|Competence[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competence->contains($competence)) {
            $this->competence->removeElement($competence);
        }

        return $this;
    }
}

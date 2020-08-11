<?php

namespace App\Entity;

use App\Entity\GroupeCompetence;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiResource(
   *     attributes={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","pagination_items_per_page"=2},
    *     collectionOperations={
    *         "post"={"security"="is_granted('ROLE_ADMIN'))", "security_message"="vous n'avez le droit de faire cette action de suppression, seul l'admin.","path"="admin/referentiels"},
    *         "get"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')", "security_message"="Vous n'avez pas acces a cette ressource.","path"="admin/referentiels"
    *         },
    *         "get_groupeCompetences"={"method"="get", "security"="is_granted('ROLE_ADMIN') or  is_granted('ROLE_CM') ", "security_message"="Vous n'avez pas acces a cette ressource.","path"="admin/referentiels/groupe_competences"},
    *     },
    *     
    *     itemOperations={
    *         "get"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')",
    *           "security_message"="Vous n'etes pas autorisé à faire cette action de lister.",
    *           "path"="admin/referentiels/{id}"}, 
    *         "grpCompet_Ref"={"method"="get",
    *            "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')",
    *            "security_message"="Vous n'etes pas autorisé à faire cette action de lister.","path"="admin/referentiels/{id}/groupe_competences/{num}"}, 
    *         "put"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
    *            "security_message"="Vous n'etes pas autorisé à faire cette action de modification.",
    *            "path"="admin/referentiels/{id}"},
    *     "archivage"={"method"="put","security"="is_granted('ROLE_ADMIN') ",
    *              "security_message"="Seul l'admin a accès à cette ressource",
    *              "path"="/admin/referentiels/{id}/archivage"}
    *  }
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ref_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "libelle can't be null")
     * @Groups({"ref_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message = "presentation can't be null")
     * @Groups({"ref_read"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"ref_read"})
     */
    private $programme;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"ref_read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"ref_read"})
     */
    private $critereAdmission;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiel")
     */
    private $promos;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(?string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(?string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(?string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

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
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
        }

        return $this;
    }


    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promos): self
    {
        if (!$this->promos->contains($promos)) {
            $this->promos[] = $promos;
            $promos->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promos): self
    {
        if ($this->promos->contains($promos)) {
            $this->promos->removeElement($promos);
            // set the owning side to null (unless already changed)
            if ($promos->getReferentiel() === $this) {
                $promos->setReferentiel(null);
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
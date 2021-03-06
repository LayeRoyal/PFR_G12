<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 *  @ApiResource(
    *     collectionOperations={
    *         "post"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))", "security_message"="Action non authorisée.","path"="/admin/promo"},
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))", "security_message"="Vous n'avez pas acces a cette ressource.","path"="/admin/promo"},
    *         "list_grp_principal"={
    *            "method"="GET",
    *            "path"="/admin/promo/principal",
    *            "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *            "security_message"="Vous n'avez pas access à cette Ressource"
    *          },
    *         "waiting_list_all_students"={
    *            "method"="GET",
    *            "path"="/admin/promo/apprenants/attente",          
    *           "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *           "security_message"="Vous n'avez pas access à cette Ressource"
    *          }
    *     },
    *     
    *     itemOperations={
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')","security_message"="Seul un admin peut faire cette action.","path"="admin/promo/{id}"},
    *            "detail_one_grp_principal"={
    *              "method"="GET",
    *              "path"="/admin/promo/{id}/principal",
    *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *              "security_message"="Vous n'avez pas access à cette Ressource"
    *            },
    *            "referentiel_promo"={
    *              "method"="GET",
    *              "path"="/admin/promo/{id}/referentiels",          
    *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *              "security_message"="Vous n'avez pas access à cette Ressource"
    *            },
    *            "waiting_list_one_promo"={
    *              "method"="GET",
    *              "path"="/admin/promo/{id}/apprenants/attente",          
    *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') )",
    *              "security_message"="Vous n'avez pas access à cette Ressource"
    *            },
    *            "grp_students_of_one_promo"={
    *              "method"="GET",
    *              "path"="/admin/promo/{id}/groupes/{num}/apprenants",          
    *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *              "security_message"="Vous n'avez pas access à cette Ressource"
    *            },
    *            "formers_one_promo"={
    *              "method"="GET",
    *              "path"="/admin/promo/{id}/formateurs",          
    *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
    *              "security_message"="Vous n'avez pas access à cette Ressource"
    *            },
    *         "put"={"security_post_denormalize"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","security_message"="Seul un admin peut faire cette action.","path"="admin/promo/{id}",},
    *            "add_del_students_one_promo"={
    *              "method"="PUT",
    *              "path"="/admin/promo/{id}/apprenants",          
    *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *              "security_message"="Vous n'avez pas access à cette Ressource"
    *            },
    *            "add_del_formers_one_promo"={
    *              "method"="PUT",
    *              "path"="/admin/promo/{id}/formateurs",          
    *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *              "security_message"="Vous n'avez pas access à cette Ressource"
    *            },
    *            "put_promo_grp_status"={
    *              "method"="PUT",
    *              "path"="/admin/promo/{id}/groupes/{num}",          
    *              "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *              "security_message"="Vous n'avez pas access à cette Ressource"
    *            }
    *  }
 * )
 */
class Promo
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
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo_read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFinProvisoire;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateFinReelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="promos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos")
     */
    private $referentiel;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos")
     */
    private $formateurs;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promo", orphanRemoval=true)
     */
    private $apprenants;

    /**
     * @ORM\OneToMany(targetEntity=PromoBrief::class, mappedBy="promo")
     */
    private $promoBriefs;

    /**
     * @ORM\OneToMany(targetEntity=StatisticCompetence::class, mappedBy="promo")
     */
    private $statisticCompetences;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->promoBriefs = new ArrayCollection();
        $this->statisticCompetences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinProvisoire(): ?\DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(\DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getDateFinReelle(): ?\DateTimeInterface
    {
        return $this->dateFinReelle;
    }

    public function setDateFinReelle(\DateTimeInterface $dateFinReelle): self
    {
        $this->dateFinReelle = $dateFinReelle;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromo($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getPromo() === $this) {
                $groupe->setPromo(null);
            }
        }

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

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

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setPromo($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            // set the owning side to null (unless already changed)
                $apprenant->setPromo(null);
            
        }

        return $this;
    }

    /**
     * @return Collection|PromoBrief[]
     */
    public function getPromoBriefs(): Collection
    {
        return $this->promoBriefs;
    }

    public function addPromoBrief(PromoBrief $promoBrief): self
    {
        if (!$this->promoBriefs->contains($promoBrief)) {
            $this->promoBriefs[] = $promoBrief;
            $promoBrief->setPromo($this);
        }

        return $this;
    }

    public function removePromoBrief(PromoBrief $promoBrief): self
    {
        if ($this->promoBriefs->contains($promoBrief)) {
            $this->promoBriefs->removeElement($promoBrief);
            // set the owning side to null (unless already changed)
            if ($promoBrief->getPromo() === $this) {
                $promoBrief->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StatisticCompetence[]
     */
    public function getStatisticCompetences(): Collection
    {
        return $this->statisticCompetences;
    }

    public function addStatisticCompetence(StatisticCompetence $statisticCompetence): self
    {
        if (!$this->statisticCompetences->contains($statisticCompetence)) {
            $this->statisticCompetences[] = $statisticCompetence;
            $statisticCompetence->setPromo($this);
        }

        return $this;
    }

    public function removeStatisticCompetence(StatisticCompetence $statisticCompetence): self
    {
        if ($this->statisticCompetences->contains($statisticCompetence)) {
            $this->statisticCompetences->removeElement($statisticCompetence);
            // set the owning side to null (unless already changed)
            if ($statisticCompetence->getPromo() === $this) {
                $statisticCompetence->setPromo(null);
            }
        }

        return $this;
    }
}

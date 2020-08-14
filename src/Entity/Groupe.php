<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
   *     attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))", "security_message"="Seul un admin peut faire cette action.","path"="admin/groupes"},
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')) ", "security_message"="Vous n'avez pas acces a cette ressource.","path"="admin/groupes"}
    *     },
    *     
    *     itemOperations={
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","security_message"="Seul un admin peut faire cette action.","path"="admin/groupes/{id}"}, 
    *         "get_grp_students"={
    *           "method"="GET",
    *           "path"="admin/groupes/{id}/apprenants",
    *           "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *           "security_message"="Vous n'avez pas access à cette Ressource"
    *          },
    *         "put"={"security_post_denormalize"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","security_message"="Seul un admin peut faire cette action.","path"="admin/groupes/{id}/apprenants"},
    *         "delete"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","security_message"="Seul un admin peut faire cette action.","path"="admin/groupes/{id}"},
    *         "del_grp_student"={
    *           "method"="DELETE",
    *           "path"="admin/groupes/{id}/apprenants/{num}" ,
    *           "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *           "security_message"="Vous n'avez pas access à cette Ressource"
    *          }
    *     }
    *)
 */
class Groupe
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbreApprenant;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     */
    private $formateurs;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     */
    private $apprenants;

    public function __construct()
    {
        $this->formateurs = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getNbreApprenant(): ?int
    {
        return $this->nbreApprenant;
    }

    public function setNbreApprenant(?int $nbreApprenant): self
    {
        $this->nbreApprenant = $nbreApprenant;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

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
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
        }

        return $this;
    }
}

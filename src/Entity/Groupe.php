<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
   *     attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))", "security_message"="Seul un admin peut faire cette action.","path"="admin/groupes"},
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')) ", "security_message"="Vous n'avez pas acces a cette ressource.","path"="admin/groupes"},
    *         "get_all_grp_students"={
    *           "method"="GET",
    *           "path"="admin/groupes/apprenants" ,
    *           "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *           "security_message"="Vous n'avez pas access à cette Ressource"
    *          }
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
    *         "put"={"security_post_denormalize"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","security_message"="Seul un admin peut faire cette action.","path"="admin/groupes/{id}"},
    *         "delete"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","security_message"="Seul un admin peut faire cette action.","path"="admin/groupes/{id}"},
    *         "del_grp_student"={
    *           "method"="GET",
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
}

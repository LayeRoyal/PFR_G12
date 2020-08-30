<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * attributes={"security"="is_granted('ROLE_ADMIN')",
 *              "pagination_items_per_page"=5,
 *             },
 *         normalizationContext={"groups"={"profilSortie_details_read", "user_read"}},
 *  collectionOperations={
 *     "profilSorties"={
 *              "method"="GET",
 *              "security_message"="Vous n'avez pas accès à la liste des profils de sortie",
 *              "path"="admin/profilsorties"
 *           },
 *     
 *      "ApprenantPromoProfilSorties"={
 *              "method"="GET",
 *              "security_message"="Vous n'avez pas accès à la liste des apprenants d'une promo par profil de sortie",
 *              "path"="admin/promo/id/profilsorties",
 *              },
 * 
 *      "addProfilSorties"={
 *              "method"="POST",
 *              "security_message"="Vous n'êtes pas autorisé à créer un profil de sortie",
 *              "path"="admin/profilsorties"
 *          }
 *   },
 *   itemOperations={
 *      
 *      "profilSortieApprenant"={
 *              "method"="GET",
 *              "security_message"="Vous n'avez pas accès à la liste des profil de sortie",
 *              "path"="admin/profilsorties/{id}"
 *             },
 * 
 *      "updateProfilSortie"={
 *              "method"="PUT",
 *              "security_message"="Vous n'êtes pas autorisé à modifier un profil de sortie",
 *              "path"="admin/profilsorties/{id}"
 *             },
 * 
 *       "list_apprenant_profil_promo"={
 *           "method"="GET",
 *           "security"="is_granted('ROLE_ADMIN') ",
 *              "security_message"="Seul l'admin a accès à cette ressource",
 *              "path"="/admin/promo/{id}/profilsorties/{num}"},
 * 
 *         "archive"={
 *              "method"="put",
 *              "security"="is_granted('ROLE_ADMIN') ",
 *              "security_message"="Seul l'admin a accès à cette ressource",
 *              "path"="/admin/profilSorties/{id}/archivage"}
 *          }
 * )
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 */
class ProfilSortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"profilSortie_details_read","user_read","user_details_read"})
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @Groups({"profilSortie_details_read","user_read","user_details_read"})
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     */
    private $apprenants;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="profilSorties")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
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
            $apprenant->setProfilSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilSortie() === $this) {
                $apprenant->setProfilSortie(null);
            }
        }

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

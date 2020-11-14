<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * attributes={"security"="is_granted('ROLE_ADMIN')",
 *              "pagination_items_per_page"=5,
 *             },
 *  collectionOperations={
 *     "GET"={
 *              "security_message"="Vous n'avez pas accès à la liste des profils de sortie",
 *              "path"="admin/profils_sortie"
 *           },
 *     
 *  
 *      "POST"={
 *              "security_message"="Vous n'êtes pas autorisé à créer un profil de sortie",
 *              "path"="admin/profils_sortie"
 *          }
 *   },
 *   itemOperations={
 *      
 *      "GET"={
 *              "security_message"="Vous n'avez pas accès à la liste des profil de sortie",
 *              "path"="admin/profils_sortie/{id}"
 *             },
 * 
 *      "PUT"={
 *              "security_message"="Vous n'êtes pas autorisé à modifier un profil de sortie",
 *              "path"="admin/profils_sortie/{id}"
 *             },
 * 
 *     "DELETE"={
 *              "security_message"="Vous n'êtes pas autorisé à supprimer un profil de sortie",
 *              "path"="admin/profils_sortie/{id}"
 *              }
 *      }
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
     * @ORM\Column(type="string", length=255)
     * @Groups({"stats_read"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilSortie")
     */
    private $apprenants;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="profilSorties")
     */
    private $createdBy;

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
}

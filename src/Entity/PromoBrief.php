<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromoBriefRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={
    *          "security"="is_granted('ROLE_ADMIN') ",
    *          "security_message"="Seul un admin peut faire cette action.",
    *          "path"="formateurs/promo/{id}/brief/{num}"
    *           },
    *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="formateurs/promo_briefs"
 *              }
    *     },
    *     
    *     itemOperations={
    *       "get"={
    *              "security"="is_granted('ROLE_ADMIN')", 
    *              "security_message"="Vous n'avez pas acces a cette ressource.",
    *              "path"="formateurs/promo_briefs/{id}"
    *              },
    *         "put"={"security_post_denormalize"="is_granted('ROLE_ADMIN')",
    *                "security_message"="Seul un admin peut faire cette action.",
    *                "path"="formateurs/promo/{id}/brief/{num}/livrablepartiels"
    *           }
    *  })
 * @ORM\Entity(repositoryClass=PromoBriefRepository::class)
 */
class PromoBrief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"lr_read"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="promoBriefs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"brief_read"})
     */
    private $brief;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="promoBriefs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $promo;

    /**
     * @ORM\OneToMany(targetEntity=LivrablePartiel::class, mappedBy="promoBrief")
     * @Groups({"lr_read"})
     */
    private $livrablePartiels;

    /**
     * @ORM\OneToMany(targetEntity=PromoBriefApprenant::class, mappedBy="promoBrief")
     */
    private $promoBriefApprenants;

    public function __construct()
    {
        $this->livrablePartiels = new ArrayCollection();
        $this->promoBriefApprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

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
            $livrablePartiel->setPromoBrief($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels->removeElement($livrablePartiel);
            // set the owning side to null (unless already changed)
            if ($livrablePartiel->getPromoBrief() === $this) {
                $livrablePartiel->setPromoBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PromoBriefApprenant[]
     */
    public function getPromoBriefApprenants(): Collection
    {
        return $this->promoBriefApprenants;
    }

    public function addPromoBriefApprenant(PromoBriefApprenant $promoBriefApprenant): self
    {
        if (!$this->promoBriefApprenants->contains($promoBriefApprenant)) {
            $this->promoBriefApprenants[] = $promoBriefApprenant;
            $promoBriefApprenant->setPromoBrief($this);
        }

        return $this;
    }

    public function removePromoBriefApprenant(PromoBriefApprenant $promoBriefApprenant): self
    {
        if ($this->promoBriefApprenants->contains($promoBriefApprenant)) {
            $this->promoBriefApprenants->removeElement($promoBriefApprenant);
            // set the owning side to null (unless already changed)
            if ($promoBriefApprenant->getPromoBrief() === $this) {
                $promoBriefApprenant->setPromoBrief(null);
            }
        }

        return $this;
    }
}

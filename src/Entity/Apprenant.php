<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
    *     attributes={"pagination_items_per_page"=10},
    *     normalizationContext ={"groups"={"user_read"}},
    *     collectionOperations={
    *         "post"={"security"="is_granted('ROLE_ADMIN')", "security_message"="Seul un admin peut faire cette action.","path"="/apprenants"},
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))", "security_message"="Vous n'avez pas acces a cette ressource.","path"="/apprenants"
    *         }
    *     },
    *     
    *     itemOperations={
    *         "get"={"security"="is_granted('ROLE_ADMIN')","security_message"="Vous n'avez pas acces a cette ressource.","path"="/apprenants/{id}"}, 
    *         "delete"={"security"="is_granted('ROLE_ADMIN')","security_message"="Seul un admin peut faire cette action.","path"="/apprenants/{id}",},
    *         "put"={"security_post_denormalize"="is_granted('ROLE_ADMIN')","security_message"="Seul un admin peut faire cette action.","path"="/apprenants/{id}",},
    *  }
  * )
 */
class Apprenant extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_read","user_details_read"})
     */
    protected $genre;

    /**
     * @ApiSubresource()
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     * @Groups({"user_details_read", "profilSortie_details_read","user_read"})
     */
    private $profilSortie;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenants")
     */
    private $groupes;

    /**
     * @ApiSubresource()
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="apprenants")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"user_read"})
     * 
     */
    private $promo;

    /**
     * @ORM\OneToMany(targetEntity=Livrable::class, mappedBy="apprenant")
     */
    private $livrables;

    /**
     * @ORM\OneToMany(targetEntity=LivrableRendu::class, mappedBy="apprenant")
     */
    private $livrableRendus;

    public function __construct()
    {
        parent::__construct();
        $this->groupes = new ArrayCollection();
        $this->livrables = new ArrayCollection();
        $this->livrableRendus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

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
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
            $groupe->removeApprenant($this);
        }

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
     * @return Collection|Livrable[]
     */
    public function getLivrables(): Collection
    {
        return $this->livrables;
    }

    public function addLivrable(Livrable $livrable): self
    {
        if (!$this->livrables->contains($livrable)) {
            $this->livrables[] = $livrable;
            $livrable->setApprenant($this);
        }

        return $this;
    }

    public function removeLivrable(Livrable $livrable): self
    {
        if ($this->livrables->contains($livrable)) {
            $this->livrables->removeElement($livrable);
            // set the owning side to null (unless already changed)
            if ($livrable->getApprenant() === $this) {
                $livrable->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LivrableRendu[]
     */
    public function getLivrableRendus(): Collection
    {
        return $this->livrableRendus;
    }

    public function addLivrableRendu(LivrableRendu $livrableRendu): self
    {
        if (!$this->livrableRendus->contains($livrableRendu)) {
            $this->livrableRendus[] = $livrableRendu;
            $livrableRendu->setApprenant($this);
        }

        return $this;
    }

    public function removeLivrableRendu(LivrableRendu $livrableRendu): self
    {
        if ($this->livrableRendus->contains($livrableRendu)) {
            $this->livrableRendus->removeElement($livrableRendu);
            // set the owning side to null (unless already changed)
            if ($livrableRendu->getApprenant() === $this) {
                $livrableRendu->setApprenant(null);
            }
        }

        return $this;
    }

}

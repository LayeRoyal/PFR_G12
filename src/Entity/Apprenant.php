<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
    *     attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={"security"="is_granted('ROLE_ADMIN')", "security_message"="Seul un admin peut faire cette action.","path"="/apprenants"},
    *         "get"={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))", "security_message"="Vous n'avez pas acces a cette ressource.","path"="/apprenants"}
    *         
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
     * @Groups({"user_read","user_details_read","stats_read","comment_read"})
     */
    protected $genre;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     * @Groups({"user_details_read","stats_read"})
     */
    private $profilSortie;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenants")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="apprenants")
     * @ORM\JoinColumn(nullable=true)
     */
    private $promo;

    /**
     * @ORM\OneToMany(targetEntity=LivrableRendu::class, mappedBy="apprenant")
     */
    private $livrableRendus;

    /**
     * @ORM\OneToMany(targetEntity=StatisticCompetence::class, mappedBy="apprenant")
     * @Groups({"stats_read"})
     */
    private $statisticCompetences;

    /**
     * @ORM\OneToMany(targetEntity=PromoBriefApprenant::class, mappedBy="apprenant")
     * @Groups({"brief_read"})
     */
    private $promoBriefApprenants;

    /**
     * @ORM\OneToMany(targetEntity=Livrables::class, mappedBy="sentBy")
     */
    private $livrables;

    public function __construct()
    {
        parent::__construct();
        $this->groupes = new ArrayCollection();
        $this->livrableRendus = new ArrayCollection();
        $this->statisticCompetences = new ArrayCollection();
        $this->promoBriefApprenants = new ArrayCollection();
        $this->livrables = new ArrayCollection();
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
            $statisticCompetence->setApprenant($this);
        }

        return $this;
    }

    public function removeStatisticCompetence(StatisticCompetence $statisticCompetence): self
    {
        if ($this->statisticCompetences->contains($statisticCompetence)) {
            $this->statisticCompetences->removeElement($statisticCompetence);
            // set the owning side to null (unless already changed)
            if ($statisticCompetence->getApprenant() === $this) {
                $statisticCompetence->setApprenant(null);
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
            $promoBriefApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removePromoBriefApprenant(PromoBriefApprenant $promoBriefApprenant): self
    {
        if ($this->promoBriefApprenants->contains($promoBriefApprenant)) {
            $this->promoBriefApprenants->removeElement($promoBriefApprenant);
            // set the owning side to null (unless already changed)
            if ($promoBriefApprenant->getApprenant() === $this) {
                $promoBriefApprenant->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Livrables[]
     */
    public function getLivrables(): Collection
    {
        return $this->livrables;
    }

    public function addLivrable(Livrables $livrable): self
    {
        if (!$this->livrables->contains($livrable)) {
            $this->livrables[] = $livrable;
            $livrable->setSentBy($this);
        }

        return $this;
    }

    public function removeLivrable(Livrables $livrable): self
    {
        if ($this->livrables->contains($livrable)) {
            $this->livrables->removeElement($livrable);
            // set the owning side to null (unless already changed)
            if ($livrable->getSentBy() === $this) {
                $livrable->setSentBy(null);
            }
        }

        return $this;
    }

}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BriefRepository::class)
  * @ApiResource(
 * attributes={
 *          "pagination_items_per_page"=10
 *      },
 *     collectionOperations={
 *         "post"={
 *          "security"="is_granted('ROLE_FORMATEUR') ",
 *          "security_message"="Seul un formateur peut faire cette action.",
 *          "path"="formateurs/briefs"
 *           },
 *          "duplicate"={
 *          "method"="POST",
 *          "security"="is_granted('ROLE_FORMATEUR') ",
 *          "security_message"="Seul un formateur peut faire cette action.",
 *          "path"="formateurs/briefs/{id}"
 *           },
 *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="formateurs/briefs"
 *              },
 *         "briefInPromo"={
 *              "method"="GET",
 *              "security_post_denormalize"="is_granted('ROLE_FORMATEUR')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="formateurs/promo/{id_promo}/brief/{id_brief}"
 *         }
 *     },  
 *     itemOperations={ 
 *          
 *         "putPromo"={
 *              "method"="PUT",
 *              "security_post_denormalize"="is_granted('ROLE_FORMATEUR')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="formateurs/promo/{id_promo}/brief/{id_brief}"
 *         },
 *         "get"={
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="formateurs/briefs/{id}"
 *         }
 *     }
 * )
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brief"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief_read","brief"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief_read","brief"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"brief"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"brief"})
     */
    private $archivage;

    /**
     * @ORM\OneToMany(targetEntity=Livrable::class, mappedBy="brief")
     */
    private $livrables;

    /**
     * @ORM\OneToMany(targetEntity=PromoBrief::class, mappedBy="brief")
     */
    private $promoBriefs;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief"})
     */
    private $modaliteEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief"})
     */
    private $critereDePerformance;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief"})
     */
    private $modalitePedagogique;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief"})
     */
    private $contexte;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brief"})
     */
    private $langue;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, inversedBy="briefs")
     */
    private $livrableAttendu;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @Groups({"brief"})
     */
    private $createdBy;

    /**ff
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs", nullable=true)
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="briefs")
     * @Groups({"brief"})
     */
    private $referentiel;

    /**
     * @ORM\OneToMany(targetEntity=BriefGroupe::class, mappedBy="brief")
     */
    private $briefGroupes;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     */
    private $ressources;

    public function __construct()
    {
        $this->livrables = new ArrayCollection();
        $this->promoBriefs = new ArrayCollection();
        $this->livrableAttendu = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->briefGroupes = new ArrayCollection();
        $this->ressources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

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
            $livrable->setBrief($this);
        }

        return $this;
    }

    public function removeLivrable(Livrable $livrable): self
    {
        if ($this->livrables->contains($livrable)) {
            $this->livrables->removeElement($livrable);
            // set the owning side to null (unless already changed)
            if ($livrable->getBrief() === $this) {
                $livrable->setBrief(null);
            }
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
            $promoBrief->setBriefs($this);
        }

        return $this;
    }

    public function removePromoBrief(PromoBrief $promoBrief): self
    {
        if ($this->promoBriefs->contains($promoBrief)) {
            $this->promoBriefs->removeElement($promoBrief);
            // set the owning side to null (unless already changed)
            if ($promoBrief->getBriefs() === $this) {
                $promoBrief->setBriefs(null);
            }
        }

        return $this;
    }

    public function getAvatar()
    {
      
        if($this->avatar){
  $data = stream_get_contents($this->avatar);
       return base64_encode($data);
        }
        return;
    }
    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getModaliteEvaluation(): ?string
    {
        return $this->modaliteEvaluation;
    }

    public function setModaliteEvaluation(string $modaliteEvaluation): self
    {
        $this->modaliteEvaluation = $modaliteEvaluation;

        return $this;
    }

    public function getCritereDePerformance(): ?string
    {
        return $this->critereDePerformance;
    }

    public function setCritereDePerformance(string $critereDePerformance): self
    {
        $this->critereDePerformance = $critereDePerformance;

        return $this;
    }

    public function getModalitePedagogique(): ?string
    {
        return $this->modalitePedagogique;
    }

    public function setModalitePedagogique(string $modalitePedagogique): self
    {
        $this->modalitePedagogique = $modalitePedagogique;

        return $this;
    }

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
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

    /**
     * @return Collection|LivrableAttendu[]
     */
    public function getLivrableAttendu(): Collection
    {
        return $this->livrableAttendu;
    }

    public function addLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if (!$this->livrableAttendu->contains($livrableAttendu)) {
            $this->livrableAttendu[] = $livrableAttendu;
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if ($this->livrableAttendu->contains($livrableAttendu)) {
            $this->livrableAttendu->removeElement($livrableAttendu);
        }

        return $this;
    }

    public function getCreatedBy(): ?Formateur
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Formateur $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): ?Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
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
     * @return Collection|BriefGroupe[]
     */
    public function getBriefGroupes(): Collection
    {
        return $this->briefGroupes;
    }

    public function addBriefGroupe(BriefGroupe $briefGroupe): self
    {
        if (!$this->briefGroupes->contains($briefGroupe)) {
            $this->briefGroupes[] = $briefGroupe;
            $briefGroupe->setBrief($this);
        }

        return $this;
    }

    public function removeBriefGroupe(BriefGroupe $briefGroupe): self
    {
        if ($this->briefGroupes->contains($briefGroupe)) {
            $this->briefGroupes->removeElement($briefGroupe);
            // set the owning side to null (unless already changed)
            if ($briefGroupe->getBrief() === $this) {
                $briefGroupe->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->contains($ressource)) {
            $this->ressources->removeElement($ressource);
            // set the owning side to null (unless already changed)
            if ($ressource->getBrief() === $this) {
                $ressource->setBrief(null);
            }
        }

        return $this;
    }
}

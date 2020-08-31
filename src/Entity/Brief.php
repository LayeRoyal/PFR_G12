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
 * @ApiResource
 * (
 *      normalizationContext={"groups"={"briefs_read"}},
 *      collectionOperations=
 *                            {
 *                              "GET"={},
 *                              "ListBriefs"={
 *                                          "path"="/formateurs/briefs",
 *                                          "method"="GET",
 *                                          "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *                                          "security_message"="Vous n'avez pas access à cette Ressource",
 *                                          "route_name"="ListeBriefs"
 *                                            },
 * 
 *                              "ListBriefsGroupPromo"={
 *                                                      "path"="/formateurs/promo/{id}/groupe/{num}/briefs",
 *                                                      "method"="GET",
 *                                                      "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *                                                      "security_message"="Vous n'avez pas access à cette Ressource",
 *                                                      "route_name"="ListeBriefsPromoGroup",
 *                                                      "normalization_context"={"groups":"briefPromo:read"},
 *                                                      
 *                                                     },
 * 
 *                              "ListBriefsBrouillons"={
 *                                                      "path"="/formateurs/{id}/briefs/brouillons",
 *                                                      "method"="GET",
 *                                                      "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                                                      "security_message"="Vous n'avez pas access à cette Ressource",
 *                                                      "route_name"="ListeBriefsBr"
 *                                                     },
 * 
 *                              "ListBriefsValides"={
 *                                                      "path"="/formateurs/{id}/briefs/valides",
 *                                                      "method"="GET",
 *                                                      "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                                                      "security_message"="Vous n'avez pas access à cette Ressource",
 *                                                      "route_name"="ListeBriefsVal"
 *                                                     },
 *                              
 *                              "FormateurBrief"={
 *                                                  "path"="/formateur/{id}/promo/{p}/brief/{b}",
 *                                                  "method"="GET",
 *                                                  "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                                                  "security_message"="Vous n'avez pas access à cette Ressource",
 *                                                  "route_name"="BriefParFormateur"
 *                                               }
 * 
 *                            
 *                             },
 *      itemOperations=
 *                      {
 *                          "GET"={},
 * 
 *                          "PutBrief"={
 *                                          "path"="/formateurs/promo/{id}/briefs/{num}",
 *                                          "method"="PUT",
 *                                          "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                                          "security_message"="Vous n'avez pas access à cette Ressource",
 *                                          "route_name"="ModifBrief"
 *                                     },
 * 
 *                          "PutBriefAssigner"={
 *                                          "path"="/formateurs/promo/{id}/brief/{num}/assignation",
 *                                          "method"="PUT",
 *                                          "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                                          "security_message"="Vous n'avez pas access à cette Ressource",
 *                                          "route_name"="ModifBriefAss"
 *                                     }
 *                      }
 * )
 */
class Brief
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @groups({"briefs_read", "briefPromo:read" })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups({"briefs_read", "briefPromo:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups({"briefs_read", "briefPromo:read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups({"briefs_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups({"briefs_read"})
     */
    private $contexte;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups({"briefs_read"})
     */
    private $ModalitesPedagogiques;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups({"briefs_read"})
     */
    private $CriteresDePerformances;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups({"briefs_read"})
     */
    private $ModalitesEvaluation;

    /**
     * @ORM\Column(type="blob")
     */
    private $avatar;

    /**
     * @ORM\Column(type="datetime")
     * @groups({"briefs_read"})
     */
    private $DateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     * @groups({"briefs_read"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @groups({"briefs_read"})
     */
    private $formateur;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="briefs")
     * @groups({"briefs_read"})
     */
    private $referentiel;

    /**
     * @ORM\OneToMany(targetEntity=Ressources::class, mappedBy="brief")
     * @groups({"briefs_read"})
     */
    private $ressources;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     * @groups({"briefs_read"})
     */
    private $tag;

    /**
     * @ORM\ManyToMany(targetEntity=LivrablesAttendus::class, inversedBy="briefs")
     * @groups({"briefs_read"})
     */
    private $LivrablesAttendus;

    /**
     * @ORM\OneToMany(targetEntity=NiveauEvaluation::class, mappedBy="brief")
     * @groups({"briefs_read"})
     */
    private $NiveauEvaluation;

    /**
     * @ORM\OneToMany(targetEntity=PromoBrief::class, mappedBy="brief")
     * @groups({"briefs_read", "briefPromo:read"})
     */
    private $PromoBrief;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, inversedBy="briefs")
     */
    private $groupe;

    public function __construct()
    {
        $this->ressources = new ArrayCollection();
        $this->tag = new ArrayCollection();
        $this->LivrablesAttendus = new ArrayCollection();
        $this->NiveauEvaluation = new ArrayCollection();
        $this->PromoBrief = new ArrayCollection();
        $this->groupe = new ArrayCollection();
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

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }

    public function getModalitesPedagogiques(): ?string
    {
        return $this->ModalitesPedagogiques;
    }

    public function setModalitesPedagogiques(string $ModalitesPedagogiques): self
    {
        $this->ModalitesPedagogiques = $ModalitesPedagogiques;

        return $this;
    }

    public function getCriteresDePerformances(): ?string
    {
        return $this->CriteresDePerformances;
    }

    public function setCriteresDePerformances(string $CriteresDePerformances): self
    {
        $this->CriteresDePerformances = $CriteresDePerformances;

        return $this;
    }

    public function getModalitesEvaluation(): ?string
    {
        return $this->ModalitesEvaluation;
    }

    public function setModalitesEvaluation(string $ModalitesEvaluation): self
    {
        $this->ModalitesEvaluation = $ModalitesEvaluation;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->DateCreation;
    }

    public function setDateCreation(\DateTimeInterface $DateCreation): self
    {
        $this->DateCreation = $DateCreation;

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

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

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
     * @return Collection|Ressources[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressources $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressources $ressource): self
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

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|LivrablesAttendus[]
     */
    public function getLivrablesAttendus(): Collection
    {
        return $this->LivrablesAttendus;
    }

    public function addLivrablesAttendu(LivrablesAttendus $livrablesAttendu): self
    {
        if (!$this->LivrablesAttendus->contains($livrablesAttendu)) {
            $this->LivrablesAttendus[] = $livrablesAttendu;
        }

        return $this;
    }

    public function removeLivrablesAttendu(LivrablesAttendus $livrablesAttendu): self
    {
        if ($this->LivrablesAttendus->contains($livrablesAttendu)) {
            $this->LivrablesAttendus->removeElement($livrablesAttendu);
        }

        return $this;
    }

    /**
     * @return Collection|NiveauEvaluation[]
     */
    public function getNiveauEvaluation(): Collection
    {
        return $this->NiveauEvaluation;
    }

    public function addNiveauEvaluation(NiveauEvaluation $niveauEvaluation): self
    {
        if (!$this->NiveauEvaluation->contains($niveauEvaluation)) {
            $this->NiveauEvaluation[] = $niveauEvaluation;
            $niveauEvaluation->setBrief($this);
        }

        return $this;
    }

    public function removeNiveauEvaluation(NiveauEvaluation $niveauEvaluation): self
    {
        if ($this->NiveauEvaluation->contains($niveauEvaluation)) {
            $this->NiveauEvaluation->removeElement($niveauEvaluation);
            // set the owning side to null (unless already changed)
            if ($niveauEvaluation->getBrief() === $this) {
                $niveauEvaluation->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PromoBrief[]
     */
    public function getPromoBrief(): Collection
    {
        return $this->PromoBrief;
    }

    public function addPromoBrief(PromoBrief $promoBrief): self
    {
        if (!$this->PromoBrief->contains($promoBrief)) {
            $this->PromoBrief[] = $promoBrief;
            $promoBrief->setBrief($this);
        }

        return $this;
    }

    public function removePromoBrief(PromoBrief $promoBrief): self
    {
        if ($this->PromoBrief->contains($promoBrief)) {
            $this->PromoBrief->removeElement($promoBrief);
            // set the owning side to null (unless already changed)
            if ($promoBrief->getBrief() === $this) {
                $promoBrief->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->contains($groupe)) {
            $this->groupe->removeElement($groupe);
        }

        return $this;
    }
}

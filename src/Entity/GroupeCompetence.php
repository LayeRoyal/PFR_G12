<?php

namespace App\Entity;

use App\Entity\Competence;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * attributes={"pagination_items_per_page"=3},
 * collectionOperations={
 *          "get"={"path"="/admin/grpecompetences"},
 *          "post"={"security"="is_granted('ROLE_ADMIN') ",
 *              "security_message"="Seul l'admin a accès à cette ressource",
 *              "path"="/admin/grpecompetences"}
 * },
 * itemOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *              "security_message"="Seul l'admin ou le formateur ou le CM a accès à cette ressource",
 *              "path"="/admin/grpecompetences/{id}"},
 *          "put"={"security"="is_granted('ROLE_ADMIN') ",
 *              "security_message"="Seul l'admin a accès à cette ressource",
 *              "path"="/admin/grpecompetences/{id}"},
 *          "archivage"={"method"="put","security"="is_granted('ROLE_ADMIN') ",
 *              "security_message"="Seul l'admin a accès à cette ressource",
 *              "path"="/admin/grpecompetences/{id}/archivage"}
 * }
 * )
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 */
class GroupeCompetence
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
    private $libelle;

    /**
     * @ORM\Column(type="text")
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupeCompetences")
     */
    private $referentiels;

     /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupecompetences", cascade={"persist"})
     */
    private $competence;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="groupeCompetences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage;

    public function __construct()
    {
        $this->referentiels = new ArrayCollection();
        $this->competence = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

     /**
     * @return Collection|Competence[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competence->contains($competence)) {
            $this->competence->removeElement($competence);
        }

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->contains($referentiel)) {
               $this->referentiels->removeElement($referentiel);
            $referentiel->removeCompetence($this);
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
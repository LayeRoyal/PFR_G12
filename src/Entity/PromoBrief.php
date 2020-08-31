<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PromoBriefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource
 * (
 *          collectionOperations={
 *                                  "GET"={},
 *                                
 *                                  
 *                                "ListBriefsPromo"={
 *                                                      "path"="/formateurs/promo/{id}/briefs",
 *                                                      "method"="GET",
 *                                                      "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *                                                      "security_message"="Vous n'avez pas access à cette Ressource",
 *                                                      "route_name"="ListeBriefsPr"
 *                                                  },
 * 
 *                                   
 *                                "ListBriefsAssigne"={
 *                                                      "path"="/apprenants/promo/{id}/briefs",
 *                                                      "method"="GET",
 *                                                      "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *                                                      "security_message"="Vous n'avez pas access à cette Ressource",
 *                                                      "route_name"="ListeBriefsAss",
 *                                                      "normalization_context"={"groups":"briefAssign:read"}
 *                                                  },
 *                                 },
 *          itemOperations={
 *                              "GET"={},
 * 
 *                              "BriefPromo"={
 *                                                      "path"="/formateurs/promo/{id}/briefs/{num}",
 *                                                      "method"="GET",
 *                                                      "security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *                                                      "security_message"="Vous n'avez pas access à cette Ressource",
 *                                                      "route_name"="ListeBriefsOnePr"
 *                                                     }
 *                         }
 * )
 * @ORM\Entity(repositoryClass=PromoBriefRepository::class)
 */
class PromoBrief
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
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="PromoBrief")
     * @groups({"briefAssign:read"})
     * 
     */
    private $brief;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="PromoBrief")
     * @groups({"briefAssign:read"})
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="promoBriefs")
     * @groups({"briefAssign:read"})
     */
    private $statutBrief;

    public function __construct()
    {
        $this->statutBrief = new ArrayCollection();
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
     * @return Collection|Apprenant[]
     */
    public function getStatutBrief(): Collection
    {
        return $this->statutBrief;
    }

    public function addStatutBrief(Apprenant $statutBrief): self
    {
        if (!$this->statutBrief->contains($statutBrief)) {
            $this->statutBrief[] = $statutBrief;
        }

        return $this;
    }

    public function removeStatutBrief(Apprenant $statutBrief): self
    {
        if ($this->statutBrief->contains($statutBrief)) {
            $this->statutBrief->removeElement($statutBrief);
        }

        return $this;
    }
}

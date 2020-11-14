<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *   attributes={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","pagination_items_per_page"=3},
 *   collectionOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *                 "security_message"="Accès non autorisé si vous êtes ni admin ni formateur ",
 *                  "path"="/admin/tags"},
 *          "post"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *                 "security_message"="Accès non autorisé si vous êtes ni admin ni formateur ",
 *                 "path"="/admin/tags"}
 *   },
 * 
 *   itemOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *                  "security_message"="Accès non autorisé si vous êtes ni admin ni formateur ",
 *                  "path"="/admin/tags/{id}"
 *             },
 *          "put"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *                 "security_message"="Accès non autorisé si vous êtes ni admin ni formateur ",
 *                 "path"="/admin/tags/{id}"
 *             }        
 * } )
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
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
     * @ORM\Column(type="string", length=255)
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, inversedBy="tags")
     */
    private $groupeTags;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="tags")
     */
    private $briefs;


    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->contains($groupeTag)) {
            $this->groupeTags->removeElement($groupeTag);
        }

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->addTag($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            $brief->removeTag($this);
        }

        return $this;
    }

    
}

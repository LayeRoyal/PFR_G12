<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *    attributes={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","pagination_items_per_page"=3},
 *    collectionOperations={
 *          "get"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *                 "security_message"="Accès non autorisé si vous êtes ni admin ni formateur ",
 *                  "path"="/admin/grptags"},
 *          "post"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *                 "security_message"="Accès non autorisé si vous êtes ni admin ni formateur ",
 *                 "path"="/admin/grptags"}
 *    },
 *    itemOperations={
 *          "voir"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')", "method"="get",
 *              "security_message"="Accès non autorisé si vous êtes ni admin ni formateur ",
 *              "path"="/admin/grptags/{id}"},
 *          "get"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *              "security_message"="Accès non autorisé si vous êtes ni admin ni formateur ",
 *              "path"="/admin/grptags/{id}/tags"},
 *          "PUT"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *                 "security_message"="Accès non autorisé si vous êtes ni admin ni formateur ",
 *                 "path"="/admin/grptags/{id}"}
 * })
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 */
class GroupeTag
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
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="groupeTags")
     */
    private $tags;

    

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addGroupeTag($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeGroupeTag($this);
        }

        return $this;
    }

   
}

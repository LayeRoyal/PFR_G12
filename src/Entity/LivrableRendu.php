<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivrableRenduRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *  @ApiResource(
 *  *     attributes={
 *          "pagination_items_per_page"=10
 *      },
 *    
 *     
 *     itemOperations={
 *         "get"={
 *              "security"="is_granted('ROLE_FORMATEUR')", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="apprenants_livrablerendu/{id}",
    *              "normalization_context"={"groups"={"comment_read"}}
 *         }, 
 *          "getAllComments"={
 *              "method"="GET",
 *              "security"="( is_granted('ROLE_FORMATEUR'),is_granted('ROLE_APPRENANT') )", 
 *              "security_message"="Vous n'avez pas ce privilege.",
 *              "path"="formateurs/livrablepartiels/{id}/commentaires",
    *              "normalization_context"={"groups"={"comment_read"}}
 *         },
 *         
 *         "put"={
 *              "security_post_denormalize"="is_granted('ROLE_FORMATEUR')", 
 *              "security_post_denormalize_message"="Vous n'avez pas ce privilege.",
 *              "path"="apprenants/{id}/livrablepartiels/{num}",
 *              "normalization_context"={"groups"={"lr_read"}}
 *         }
 *     }
 * )
 * @ORM\Entity(repositoryClass=LivrableRenduRepository::class)
 */
class LivrableRendu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *@Groups({"comment_read","lr_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"comment_read","lr_read"})
     */
    private $statut;

    /**
     * @ORM\Column(type="datetime")
     *@Groups({"comment_read","lr_read"})
     */
    private $delai;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="livrableRendus")
     *@Groups({"comment_read"})
     */
    private $apprenant;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="livrableRendu")
     */
    private $commentaires;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablePartiel::class, inversedBy="livrableRendus")
     *@Groups({"comment_read","lr_read"})
     */
    private $livrablePartiel;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
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

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setLivrableRendu($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getLivrableRendu() === $this) {
                $commentaire->setLivrableRendu(null);
            }
        }

        return $this;
    }

    public function getLivrablePartiel(): ?LivrablePartiel
    {
        return $this->livrablePartiel;
    }

    public function setLivrablePartiel(?LivrablePartiel $livrablePartiel): self
    {
        $this->livrablePartiel = $livrablePartiel;

        return $this;
    }
}

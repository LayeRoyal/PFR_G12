<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *   @ApiResource(
 *     attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "postedByFormateur"={
    *           "method"="POST",
    *          "path"="formateurs/livrablepartiels/{id}/commentaires"
    *           },
    *           "postedByApprenant"={
    *           "method"="POST",
    *          "path"="apprenants/livrablepartiels/{id}/commentaires"
    *           }
    *     },
    *     
    *     itemOperations={
    *       "get"={
    *              "security"="is_granted('ROLE_FORMATEUR')", 
    *              "security_message"="Vous n'avez pas acces a cette ressource.",
    *              "path"="formateurs_livrablepartiels/commentaires/{id}",
    *              "normalization_context"={"groups"={"comment_read"}}
    *              }
    *  })
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *@Groups({"comment_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *@Groups({"comment_read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="commentaires")
     *@Groups({"comment_read"})
     */
    private $formateur;
    /**
     * @ORM\ManyToOne(targetEntity=LivrableRendu::class, inversedBy="commentaires")
     *@Groups({"comment_read"})
     */
    private $livrableRendu;

    /**
     * @ORM\Column(type="blob", nullable=true)
     *@Groups({"comment_read"})
     */
    private $pieceJointe;

    /**
     * @ORM\Column(type="datetime")
     *@Groups({"comment_read"})
     */
    private $createdAt;

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

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }


    public function getLivrableRendu(): ?LivrableRendu
    {
        return $this->livrableRendu;
    }

    public function setLivrableRendu(?LivrableRendu $livrableRendu): self
    {
        $this->livrableRendu = $livrableRendu;

        return $this;
    }

    public function getPieceJointe()
    {
        if($this->pieceJointe){
        $data = stream_get_contents($this->pieceJointe);

       return base64_encode($data);
        }
        
    }
    public function setPieceJointe($pieceJointe): self
    {
        $this->pieceJointe = $pieceJointe;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

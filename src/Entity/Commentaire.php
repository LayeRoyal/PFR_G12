<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="commentaires")
     */
    private $formateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pj;

    /**
     * @ORM\ManyToOne(targetEntity=LivrableRendu::class, inversedBy="commentaires")
     */
    private $livrableRendu;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

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

    public function getPj(): ?string
    {
        return $this->pj;
    }

    public function setPj(string $pj): self
    {
        $this->pj = $pj;

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

}

<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *  attributes={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')"
 *             },
 * collectionOperations={
 *      "GET"={
 *            "method"="GET",
 *            "security_message"="Vous ne pouvez acceder à lire ce achat",
 *            "path"="users/promo/{id}/apprenant/{num}/chats"
 *           },
 * 
 *      "POST"={
 *           "method"="POST",
 *           "security_message"="Vous ne pouvez acceder à ecrire dans ce achat",
 *           "path"="users/promo/{id}/apprenant/{num}/chats"
 *           }
 *  }
 * )
 * 
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 */
class Chat
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
    private $message;

    /**
     * @ORM\Column(type="blob",nullable=true)
     */
    private $pieceJointes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="chats")
     */
    private $promo;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPieceJointes()
    {
        $pj= $this->pieceJointes;
        if(!empty($pj)){
            return base64_encode(stream_get_contents($pj));
        }
        return $pj;
   
    }

    public function setPieceJointes($pieceJointes): self
    {
        $this->pieceJointes = $pieceJointes;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

}

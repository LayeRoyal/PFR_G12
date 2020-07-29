<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
   *     attributes={"security"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))","pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={"security"="is_granted('ROLE_ADMIN') ", "security_message"="Seul un admin peut faire cette action.","path"="admin/users"},
    *         "get"={"security"="is_granted('ROLE_ADMIN')  ", "security_message"="Vous n'avez pas acces a cette ressource.","path"="admin/users",
    *         "normalization_context"={"groups"={"user_read"}}},
    *         "add_user"={
*               "method"="POST",
*               "path"="/admin/users",
*               "security"="is_granted('ROLE_ADMIN') ",
*               "security_message"="Vous n'avez pas access à cette Ressource"
    *          },
    *         "get_apprenants"={
    *           "method"="GET",
    *           "path"="/apprenants" ,
    *           "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
    *           "access_control_message"="Vous n'avez pas access à cette Ressource",
    *           "route_name"="apprenant_liste",
    *           "normalization_context"={"groups"={"apprenant_read"}}
    *          }
    *     },
    *     
    *     itemOperations={
    *         "get"={"security"="is_granted('ROLE_ADMIN')","security_message"="Seul un admin peut faire cette action.","path"="admin/users/{id}", "normalization_context"={"groups"={"user_details_read"}},}, 
    *         "delete"={"security"="is_granted('ROLE_ADMIN')","security_message"="Seul un admin peut faire cette action.","path"="admin/users/{id}",},
    *         "patch"={"security"="is_granted('ROLE_ADMIN')","security_message"="Seul un admin peut faire cette action.","path"="admin/users/{id}",},
    *         "put"={"security_post_denormalize"="is_granted('ROLE_ADMIN')","security_message"="Seul un admin peut faire cette action.","path"="admin/users/{id}",},
    *  }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_read","user_details_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "username can't be null")
     * @Groups({"user_read","user_details_read"})
     */
    private $username;
    /**
     * 
     */
  
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user_details_read"})
     * @Assert\NotBlank(message = "password can't be null")
     */
    private $password;

    /**
     * @ORM\Column(type="blob", nullable=false)
     * @Assert\NotBlank(message = "avatar can't be null")
     * @Groups({"user_details_read"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "prenom can't be null")
     * @Groups({"user_read","user_details_read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "nom can't be null")
     * @Groups({"user_read","user_details_read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Email can't be null")
     * @Assert\Email(
     *  message = "Email '{{ value }}' is not valid!."
     *)
     *@Groups({"user_details_read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_details_read"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_details_read"})
     */
    private $profil;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }
}

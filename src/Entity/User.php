<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="profil", type="string")
 * @ORM\DiscriminatorMap({"ADMIN" = "User", "APPRENANT" = "Apprenant", "FORMATEUR" = "Formateur", "CM" = "Cm"})
 * @ApiResource(
 *     attributes={"pagination_items_per_page"=10},
    *     collectionOperations={
    *         "post"={
    *          "security"="is_granted('ROLE_ADMIN') ",
    *          "security_message"="Seul un admin peut faire cette action.",
    *          "path"="admin/users"
    *           },
    *          "get"={
 *              "security"="is_granted('ROLE_ADMIN')", 
 *              "security_message"="Vous n'avez pas acces a cette ressource.",
 *              "path"="admin/users",
    *           "normalization_context"={"groups"={"user_read"}}
 *              }
    *     },
    *     
    *     itemOperations={
    *         "get"={"security"="is_granted('ROLE_ADMIN')",
    *            "security_message"="Seul un admin peut faire cette action.",
    *            "path"="admin/users/{id}", 
    *            "normalization_context"={"groups"={"user_details_read"}}
    *            }, 
    *         "delete"={"security"="is_granted('ROLE_ADMIN')",
    *                   "security_message"="Seul un admin peut faire cette action.",
    *                   "path"="admin/users/{id}"},
    *         "put"={"security_post_denormalize"="is_granted('ROLE_ADMIN')",
    *                "security_message"="Seul un admin peut faire cette action.",
    *                "path"="admin/users/{id}"}
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
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_read","user_details_read"})
     */
    protected $username;

    /**
     * @Groups({"user_read","user_details_read"})
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="blob", nullable=false)
     * @Assert\NotBlank(message = "avatar can't be null")
     * @Groups({"user_details_read"})
     */
    protected $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "prenom can't be null")
     * @Groups({"user_read","user_details_read"})
     */
    protected $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "nom can't be null")
     * @Groups({"user_read","user_details_read"})
     */
    protected $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Email can't be null")
     * @Assert\Email(
     *  message = "Email '{{ value }}' is not valid!."
     *)
     *@Groups({"user_details_read", "user_read"})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_details_read"})
     */
    protected $statut;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_details_read"})
     */
    protected $profil;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="createdBy")
     */
    private $promos;

    /**
     * @ORM\OneToMany(targetEntity=ProfilSortie::class, mappedBy="createdBy")
     */
    private $profilSorties;

    /**
     * @ORM\OneToMany(targetEntity=GroupeCompetence::class, mappedBy="createdBy", orphanRemoval=true)
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="user")
     */
    private $chats;

    public function __construct()
    {
        $this->promos = new ArrayCollection();
        $this->profilSorties = new ArrayCollection();
        $this->groupeCompetences = new ArrayCollection();
        $this->chats = new ArrayCollection();
    }


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
        $roles[] = 'ROLE_' . $this->profil->getLibelle();

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
        $data = stream_get_contents($this->avatar);
        fclose($this->avatar);

       return base64_encode($data);
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

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setCreatedBy($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            // set the owning side to null (unless already changed)
            if ($promo->getCreatedBy() === $this) {
                $promo->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProfilSortie[]
     */
    public function getProfilSorties(): Collection
    {
        return $this->profilSorties;
    }

    public function addProfilSorty(ProfilSortie $profilSorty): self
    {
        if (!$this->profilSorties->contains($profilSorty)) {
            $this->profilSorties[] = $profilSorty;
            $profilSorty->setCreatedBy($this);
        }

        return $this;
    }

    public function removeProfilSorty(ProfilSortie $profilSorty): self
    {
        if ($this->profilSorties->contains($profilSorty)) {
            $this->profilSorties->removeElement($profilSorty);
            // set the owning side to null (unless already changed)
            if ($profilSorty->getCreatedBy() === $this) {
                $profilSorty->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->setCreatedBy($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            // set the owning side to null (unless already changed)
            if ($groupeCompetence->getCreatedBy() === $this) {
                $groupeCompetence->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->contains($chat)) {
            $this->chats->removeElement($chat);
            // set the owning side to null (unless already changed)
            if ($chat->getUser() === $this) {
                $chat->setUser(null);
            }
        }

        return $this;
    }
}

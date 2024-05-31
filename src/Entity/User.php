<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column(length: 180)] // Ajout de la colonne pour l'email
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $user_posts;

    /**
     * @var Collection<int, PostsBlocked>
     */
    #[ORM\OneToMany(targetEntity: PostsBlocked::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $postsBlockeds;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $user_comments;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'post_user')]
    private Collection $liked_posts;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'comment_user', orphanRemoval: true)]
    private Collection $liked_comments;

    #[ORM\Column]
    private bool $isVerified = false;

    public function __construct()
    {
        $this->user_posts = new ArrayCollection();
        $this->postsBlockeds = new ArrayCollection();
        $this->user_comments = new ArrayCollection();
        $this->liked_posts = new ArrayCollection();
        $this->liked_comments = new ArrayCollection();
    }


    public function getEmail(): ?string // Getter pour l'email
    {
        return $this->email;
    }

    public function setEmail(?string $email): self // Setter pour l'email
    {
        $this->email = $email;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getUserPosts(): Collection
    {
        return $this->user_posts;
    }

    public function addUserPost(Post $userPost): static
    {
        if (!$this->user_posts->contains($userPost)) {
            $this->user_posts->add($userPost);
            $userPost->setUserId($this);
        }

        return $this;
    }

    public function removeUserPost(Post $userPost): static
    {
        if ($this->user_posts->removeElement($userPost)) {
            // set the owning side to null (unless already changed)
            if ($userPost->getUserId() === $this) {
                $userPost->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PostsBlocked>
     */
    public function getPostsBlockeds(): Collection
    {
        return $this->postsBlockeds;
    }

    public function addPostsBlocked(PostsBlocked $postsBlocked): static
    {
        if (!$this->postsBlockeds->contains($postsBlocked)) {
            $this->postsBlockeds->add($postsBlocked);
            $postsBlocked->setUserId($this);
        }

        return $this;
    }

    public function removePostsBlocked(PostsBlocked $postsBlocked): static
    {
        if ($this->postsBlockeds->removeElement($postsBlocked)) {
            // set the owning side to null (unless already changed)
            if ($postsBlocked->getUserId() === $this) {
                $postsBlocked->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getUserComments(): Collection
    {
        return $this->user_comments;
    }

    public function addUserComment(Comments $userComment): static
    {
        if (!$this->user_comments->contains($userComment)) {
            $this->user_comments->add($userComment);
            $userComment->setUserId($this);
        }

        return $this;
    }

    public function removeUserComment(Comments $userComment): static
    {
        if ($this->user_comments->removeElement($userComment)) {
            // set the owning side to null (unless already changed)
            if ($userComment->getUserId() === $this) {
                $userComment->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getLikedPosts(): Collection
    {
        return $this->liked_posts;
    }

    public function addLikedPost(Post $likedPost): static
    {
        if (!$this->liked_posts->contains($likedPost)) {
            $this->liked_posts->add($likedPost);
            $likedPost->setPostUser($this);
        }

        return $this;
    }

    public function removeLikedPost(Post $likedPost): static
    {
        if ($this->liked_posts->removeElement($likedPost)) {
            // set the owning side to null (unless already changed)
            if ($likedPost->getPostUser() === $this) {
                $likedPost->setPostUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getLikedComments(): Collection
    {
        return $this->liked_comments;
    }

    public function addLikedComment(Comments $likedComment): static
    {
        if (!$this->liked_comments->contains($likedComment)) {
            $this->liked_comments->add($likedComment);
            $likedComment->setCommentUser($this);
        }

        return $this;
    }

    public function removeLikedComment(Comments $likedComment): static
    {
        if ($this->liked_comments->removeElement($likedComment)) {
            // set the owning side to null (unless already changed)
            if ($likedComment->getCommentUser() === $this) {
                $likedComment->setCommentUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}

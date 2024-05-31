<?php

namespace App\Entity;

use App\Repository\PostsBlockedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsBlockedRepository::class)]
class PostsBlocked
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1024)]
    private ?string $content = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $img_link = null;

    #[ORM\ManyToOne(inversedBy: 'postsBlockeds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    /**
     * @var Collection<int, WordsBlocked>
     */
    #[ORM\OneToMany(targetEntity: WordsBlocked::class, mappedBy: 'postsBlocked')]
    private Collection $mot_id;

    public function __construct()
    {
        $this->mot_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getImgLink(): ?string
    {
        return $this->img_link;
    }

    public function setImgLink(?string $img_link): static
    {
        $this->img_link = $img_link;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, WordsBlocked>
     */
    public function getMotId(): Collection
    {
        return $this->mot_id;
    }

    public function addMotId(WordsBlocked $motId): static
    {
        if (!$this->mot_id->contains($motId)) {
            $this->mot_id->add($motId);
            $motId->setPostsBlocked($this);
        }

        return $this;
    }

    public function removeMotId(WordsBlocked $motId): static
    {
        if ($this->mot_id->removeElement($motId)) {
            // set the owning side to null (unless already changed)
            if ($motId->getPostsBlocked() === $this) {
                $motId->setPostsBlocked(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1024)]
    private ?string $content = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $img_link = null;

    #[ORM\ManyToOne(inversedBy: 'user_posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'liked_posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $post_user = null;

    #[ORM\Column]
    private ?int $like_number = null;

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

    public function getPostUser(): ?User
    {
        return $this->post_user;
    }

    public function setPostUser(?User $post_user): static
    {
        $this->post_user = $post_user;

        return $this;
    }

    public function getLikeNumber(): ?int
    {
        return $this->like_number;
    }

    public function setLikeNumber(int $like_number): static
    {
        $this->like_number = $like_number;

        return $this;
    }
}

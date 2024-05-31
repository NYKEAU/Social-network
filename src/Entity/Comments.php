<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $likes_number = null;

    #[ORM\ManyToOne(inversedBy: 'user_comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'liked_comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $comment_user = null;

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

    public function getLikesNumber(): ?int
    {
        return $this->likes_number;
    }

    public function setLikesNumber(int $likes_number): static
    {
        $this->likes_number = $likes_number;

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

    public function getCommentUser(): ?User
    {
        return $this->comment_user;
    }

    public function setCommentUser(?User $comment_user): static
    {
        $this->comment_user = $comment_user;

        return $this;
    }
}

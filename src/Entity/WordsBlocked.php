<?php

namespace App\Entity;

use App\Repository\WordsBlockedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WordsBlockedRepository::class)]
class WordsBlocked
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $word = null;

    #[ORM\ManyToOne(inversedBy: 'mot_id')]
    private ?PostsBlocked $postsBlocked = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): static
    {
        $this->word = $word;

        return $this;
    }

    public function getPostsBlocked(): ?PostsBlocked
    {
        return $this->postsBlocked;
    }

    public function setPostsBlocked(?PostsBlocked $postsBlocked): static
    {
        $this->postsBlocked = $postsBlocked;

        return $this;
    }
}

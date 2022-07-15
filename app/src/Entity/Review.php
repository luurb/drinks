<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReviewRepository;
use App\Validator\CanBeReviewed;
use App\Validator\IsValidUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['security' => "is_granted('ROLE_ADMIN')"],
        'post' => [
            'security' => "is_granted('ROLE_USER')",
            'validation_groups' => ['Default', 'postValidation']
        ]
    ],
    itemOperations: [
        'get' => ['security' => "is_granted('ROLE_USER')"],
    ]
)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $review;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $title;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[IsValidUser()]
    private $author;

    #[ORM\ManyToOne(targetEntity: Drink::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[CanBeReviewed(groups: ['postValidation'])]
    private $drink;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(string $review): self
    {
        $this->review = $review;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDrink(): ?Drink
    {
        return $this->drink;
    }

    public function setDrink(?Drink $drink): self
    {
        $this->drink = $drink;

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RatingRepository;
use App\Validator\CanBeRated;
use App\Validator\IsValidUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['rating:read']],
    denormalizationContext: ['groups' => ['rating:write']],
    collectionOperations: [
        'get' => ['security' => "is_granted('ROLE_ADMIN')"],
        'post' => [
            'security' => "is_granted('ROLE_USER')",
            'validation_groups' => ['Default', 'postValidation']
        ]
    ],
    itemOperations: [
        'get',
        'put' => ['security' => "object.getUser() == user or is_granted('ROLE_ADMIN')"],
        'patch' => ['security' => "object.getUser() == user or is_granted('ROLE_ADMIN')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"]
    ]
)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(['rating:read', 'rating:write'])]
    #[Assert\NotBlank]
    private $rating;

    #[ORM\ManyToOne(targetEntity: Drink::class, inversedBy: 'ratings')]
    #[Assert\NotBlank]
    #[Groups(['rating:write'])]
    #[CanBeRated(groups: ['postValidation'])]
    private $drink;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'ratings')]
    #[Assert\NotBlank]
    #[Groups(['rating:write'])]
    #[IsValidUser()]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post' => ['security' => "is_granted('ROLE_USER')"]
    ],
    itemOperations: [
        'get',
        'patch' => ['security' => "is_granted('ROLE_ADMIN')"],
        'put' => ['security' => "is_granted('ROLE_ADMIN')"],
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],
        ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: ['name' => 'partial']
)]
#[ApiFilter(
    BooleanFilter::class,
    properties: ['isPublished']
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: false)]
    #[Groups('drink:read')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[ApiProperty(identifier: true)]
    #[Groups('drink:read')]
    private $name;

    #[ORM\ManyToMany(targetEntity: Drink::class, mappedBy: 'products')]
    private $drinks;

    #[ORM\Column(type: 'boolean')]
    private $isPublished = false;

    public function __construct()
    {
        $this->drinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Drink>
     */
    public function getDrinks(): Collection
    {
        return $this->drinks;
    }

    public function addDrink(Drink $drink): self
    {
        if (!$this->drinks->contains($drink)) {
            $this->drinks[] = $drink;
            $drink->addProduct($this);
        }

        return $this;
    }

    public function removeDrink(Drink $drink): self
    {
        if ($this->drinks->removeElement($drink)) {
            $drink->removeProduct($this);
        }

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}

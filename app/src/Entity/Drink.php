<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\DrinkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DrinkRepository::class)]
#[ApiResource(
    attributes: [
        'pagination_enabled' => true,
        'pagination_client_enabled' => true,
        'pagination_items_per_page' => 20
    ],
    normalizationContext: ['groups' => ['drink:read']],
    collectionOperations: [
        'get',
        'post' => ['security' => "is_granted('ROLE_USER')"]
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'products' => 'exact',
        'categories' => 'exact',
    ]

)]
#[ApiFilter(PropertyFilter::class)]
class Drink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('drink:read')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('drink:read')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    private $name;

    #[ORM\Column(type: 'text')]
    #[Groups('drink:read')]
    #[Assert\NotBlank]
    private $description;

    #[ORM\Column(type: 'text')]
    #[Groups('drink:read')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    private $preparation;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('drink:read')]
    private $image;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'drinks')]
    #[Groups('drink:read')]
    private $products;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'drinks')]
    #[Groups('drink:read')]
    private $categories;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups('drink:read')]
    public function getShortDescription(): ?string
    {
        if (strlen($this->description) < 180) {
            return $this->description;
        }

        return substr($this->description, 0, 180) . '...';
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPreparation(): ?string
    {
        return $this->preparation;
    }

    public function setPreparation(string $preparation): self
    {
        $this->preparation = $preparation;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }
}

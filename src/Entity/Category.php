<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $label = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateAdd = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateUpdate = null;

    #[ORM\Column]
    private ?bool $isRootCategory = null;

    #[ORM\Column]
    private ?int $positionning = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'categories')]
    private Collection $products;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childrenCategories')]
    private ?self $parentCategory = null;

    #[ORM\OneToMany(mappedBy: 'parentCategory', targetEntity: self::class)]
    private Collection $childrenCategories;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->childrenCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeImmutable
    {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTimeImmutable $dateAdd): static
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeImmutable
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(?\DateTimeImmutable $dateUpdate): static
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    public function isIsRootCategory(): ?bool
    {
        return $this->isRootCategory;
    }

    public function setIsRootCategory(bool $isRootCategory): static
    {
        $this->isRootCategory = $isRootCategory;

        return $this;
    }

    public function getPositionning(): ?int
    {
        return $this->positionning;
    }

    public function setPositionning(int $positionning): static
    {
        $this->positionning = $positionning;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeCategory($this);
        }
        return $this;
    }

    public function getParentCategory(): ?self
    {
        return $this->parentCategory;
    }

    public function setParentCategory(?self $parentCategory = null): static
    {
        $this->parentCategory = $parentCategory ?: null;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildrenCategories(): Collection
    {
        return $this->childrenCategories;
    }

    public function addChildrenCategory(self $childrenCategory): static
    {
        if (!$this->childrenCategories->contains($childrenCategory)) {
            $this->childrenCategories->add($childrenCategory);
            $childrenCategory->setParentCategory($this);
        }

        return $this;
    }

    public function removeChildrenCategory(self $childrenCategory): static
    {
        if ($this->childrenCategories->removeElement($childrenCategory)) {
            // set the owning side to null (unless already changed)
            if ($childrenCategory->getParentCategory() === $this) {
                $childrenCategory->setParentCategory(null);
            }
        }

        return $this;
    }
}

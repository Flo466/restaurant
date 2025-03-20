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

    #[ORM\Column(length: 64)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $uodatedAt = null;

    /**
     * @var Collection<int, Menu>
     */
    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'categoryId')]
    private Collection $menuId;

    /**
     * @var Collection<int, Food>
     */
    #[ORM\ManyToMany(targetEntity: Food::class, mappedBy: 'categoryId')]
    private Collection $foodId;

    public function __construct()
    {
        $this->menuId = new ArrayCollection();
        $this->foodId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUodatedAt(): ?\DateTimeImmutable
    {
        return $this->uodatedAt;
    }

    public function setUodatedAt(?\DateTimeImmutable $uodatedAt): static
    {
        $this->uodatedAt = $uodatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenuId(): Collection
    {
        return $this->menuId;
    }

    public function addMenuId(Menu $menuId): static
    {
        if (!$this->menuId->contains($menuId)) {
            $this->menuId->add($menuId);
            $menuId->addCategoryId($this);
        }

        return $this;
    }

    public function removeMenuId(Menu $menuId): static
    {
        if ($this->menuId->removeElement($menuId)) {
            $menuId->removeCategoryId($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getFoodId(): Collection
    {
        return $this->foodId;
    }

    public function addFoodId(Food $foodId): static
    {
        if (!$this->foodId->contains($foodId)) {
            $this->foodId->add($foodId);
            $foodId->addCategoryId($this);
        }

        return $this;
    }

    public function removeFoodId(Food $foodId): static
    {
        if ($this->foodId->removeElement($foodId)) {
            $foodId->removeCategoryId($this);
        }

        return $this;
    }
}

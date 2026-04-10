<?php

namespace App\Dto;

use App\Entity\Category;

class ProductDto
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $slug = null;
    private ?string $description = null;
    private ?string $sku = null;
    private ?Category $category = null;
    private ?float $sellPrice = null;

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): void { $this->name = $name; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(?string $slug): void { $this->slug = $slug; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): void { $this->description = $description; }

    public function getSku(): ?string { return $this->sku; }
    public function setSku(?string $sku): void { $this->sku = $sku; }

    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): void { $this->category = $category; }

    public function getSellPrice(): ?float { return $this->sellPrice; }
    public function setSellPrice(?float $sellPrice): void { $this->sellPrice = $sellPrice; }
}

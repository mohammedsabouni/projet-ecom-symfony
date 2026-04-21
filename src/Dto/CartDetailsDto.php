<?php

namespace App\Dto;

class CartDetailsDto
{
    public function __construct(
        private int $productId,
        private string $productName,
        private string $sku,
        private float $price,
        private int $quantity,
        private float $total,
    ) {}

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}

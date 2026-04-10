<?php

namespace App\Mapper;

use App\Dto\ProductDto;
use App\Entity\Product;

class ProductMapper
{
    public function toDto(Product $product): ProductDto
    {
        $dto = new ProductDto();
        $dto->setId($product->getId());
        $dto->setName($product->getName());
        $dto->setSlug($product->getSlug());
        $dto->setDescription($product->getDescription());
        $dto->setSku($product->getSku());
        $dto->setSellPrice($product->getSellPrice());
        $dto->setCategory($product->getCategory());

        return $dto;
    }

    public function toEntity(ProductDto $dto): Product
    {
        $product = new Product();
        return $this->updateEntity($product, $dto);
    }

    public function updateEntity(Product $product, ProductDto $dto): Product
    {
        $product->setName($dto->getName());
        $product->setSlug($dto->getSlug());
        $product->setDescription($dto->getDescription());
        $product->setSku($dto->getSku());
        $product->setSellPrice($dto->getSellPrice());
        $product->setCategory($dto->getCategory());

        return $product;
    }

    /** @param Product[] $products */
    public function toDtoList(array $products): array
    {
        return array_map(fn(Product $p) => $this->toDto($p), $products);
    }
}

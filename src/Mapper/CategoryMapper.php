<?php

namespace App\Mapper;

use App\Dto\CategoryDto;
use App\Entity\Category;

class CategoryMapper
{
    public function toEntity(CategoryDto $dto): Category
    {
        $category = new Category();
        $category->setName($dto->getName());
        $category->setDescription($dto->getDescription());

        return $category;
    }

    public function toDto(Category $category): CategoryDto
    {
        $dto = new CategoryDto();
        $dto->setId($category->getId());
        $dto->setName($category->getName());
        $dto->setDescription($category->getDescription());

        return $dto;
    }

    public function updateEntity(Category $category, $dto)
    {
        $category->setName($dto->getName());
        $category->setDescription($dto->getDescription());
    }

    /** @param Category[] $categories */
    public function toDtoList(array $categories): array
    {
        return array_map(fn(Category $c) => $this->toDto($c), $categories);
    }

}

<?php

namespace App\Twig\Components;

use App\Repository\CategoryRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('category')]
final class CategoryComponent
{
    private array $categories = [];

    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function mount(): void
    {
        $this->categories = $this->categoryRepository->findAll();
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}

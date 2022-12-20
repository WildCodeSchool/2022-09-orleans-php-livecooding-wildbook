<?php

namespace App\Entity;

class DocumentSearch
{
    private ?string $input = null;

    private ?Category $category = null;


    /**
     * Set the value of input
     */
    public function setInput(?string $input): self
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Set the value of category
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of input
     */
    public function getInput(): ?string
    {
        return $this->input;
    }
}

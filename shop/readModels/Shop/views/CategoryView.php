<?php

namespace shop\readModels\Shop\views;

use shop\entities\Shop\Category;

class CategoryView
{
    public $category;
    public $count;

    public function __construct(Category $category, int $count)
    {
        $this->category = $category;
        $this->count = $count;
    }
}
<?php

namespace shop\repositories\Shop;

use shop\dispatchers\EventDispatcher;
use shop\entities\Shop\Category;
use shop\repositories\events\EntityPersisted;
use shop\repositories\events\EntityRemoved;
use shop\repositories\NotFoundException;

class CategoryRepository
{
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function get($id): Category
    {
        if( !$category = Category::findOne($id) ){
            throw new NotFoundException('Category not found!');
        }

        return $category;
    }

    public function save(Category $category): void
    {
        if( !$category->save() ){
            throw new \RuntimeException('Category saving error!');
        }
        $this->dispatcher->dispatch(new EntityPersisted($category));
    }

    public function remove(Category $category): void
    {
        if( !$category->delete() ){
            throw new \RuntimeException('Category removing error!');
        }
        $this->dispatcher->dispatch(new EntityRemoved($category));
    }
}
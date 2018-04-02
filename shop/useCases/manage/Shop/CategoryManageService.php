<?php

namespace shop\useCases\manage\Shop;

use shop\entities\Meta;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Product;
use shop\forms\manage\Shop\CategoryForm;
use shop\repositories\Shop\CategoryRepository;
use shop\repositories\Shop\ProductRepository;

class CategoryManageService
{
    //Обработка CRUD при работе с формой и репозиторием-хранилищем
    private $repository;
    private $products;

    public function __construct(CategoryRepository $repository, ProductRepository $products)
    {
        $this->repository = $repository;
        $this->products = $products;
    }

    public function create(CategoryForm $form): Category
    {
        $parent = $this->repository->get($form->parentId);

        $category = Category::create($form->name, $form->slug, $form->title, $form->description,
            new Meta($form->meta->title, $form->meta->keywords, $form->meta->description)
        );

        //Привязываем категорию к выбранной родительской
        $category->appendTo($parent);
        //Сохраняем в базу
        $this->repository->save($category);

        return $category;
    }

    public function assertIsNotRoot(Category $category): void
    {
        if( $category->isRoot() ){
            throw new \DomainException('Unable to manage root category!');
        }
    }

    public function edit($id, CategoryForm $form): void
    {
        $category = $this->repository->get($id);

        $this->assertIsNotRoot($category);

        $category->edit($form->name, $form->slug, $form->title, $form->description,
            new Meta($form->meta->title, $form->meta->keywords, $form->meta->description)
        );

        if($form->parentId !== $category->parent->id){
            $parent = $this->repository->get($form->parentId);
            //Привязываем категорию к новой выбранной родительской
            $category->appendTo($parent);
        }

        $this->repository->save($category);
    }

    public function remove($id): void
    {
        $category = $this->repository->get($id);
        $this->assertIsNotRoot($category);

        if( $this->products->existsByMainCategory($id) ){
            throw new \DomainException('Unable to remove category with products!');
        }

        $this->repository->remove($category);
    }

    public function moveUp($id): void
    {
        $category = $this->repository->get($id);
        $this->assertIsNotRoot($category);

        if($prev = $category->prev){
            $category->insertBefore($prev);
        }

        $this->repository->save($category);
    }

    public function moveDown($id): void
    {
        $category = $this->repository->get($id);
        $this->assertIsNotRoot($category);

        if($next = $category->next){
            $category->insertAfter($next);
        }

        $this->repository->save($category);
    }
}
<?php

namespace shop\services\manage\Blog;

use shop\entities\Blog\Category;
use shop\entities\Meta;
use shop\forms\manage\Blog\CategoryForm;
use shop\repositories\Blog\CategoryRepository;
use shop\repositories\Blog\PostRepository;

class CategoryManageService
{
    private $repository;
    private $posts;

    public function __construct(CategoryRepository $repository, PostRepository $posts)
    {
        $this->repository = $repository;
        $this->posts = $posts;
    }

    public function get($id): ?Category
    {
        return $this->repository->get($id);
    }

    public function add(CategoryForm $form): Category
    {
        $category = Category::create(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->keywords,
                $form->meta->description
            )
        );
        $this->repository->save($category);
        return $category;
    }

    public function edit($id, CategoryForm $form): void
    {
        $category = $this->get($id);
        $category->edit(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->keywords,
                $form->meta->description
            )
        );
        $this->repository->save($category);
    }

    public function remove($id): void
    {
        $category = $this->get($id);
        if($this->posts->existsByCategory($id)){
            throw new \DomainException('Cannot delete category with posts.');
        }
        $this->repository->remove($category);
    }
}
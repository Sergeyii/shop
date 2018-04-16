<?php

namespace shop\useCases\manage\Shop;

use shop\entities\Shop\Tag;
use shop\forms\manage\Shop\TagForm;
use shop\repositories\Shop\TagRepository;
use yii\helpers\Inflector;

class TagManageService
{
    private $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(TagForm $form): Tag
    {
        $tag = Tag::create($form->name, Inflector::slug($form->slug) );

        $this->repository->save($tag);

        return $tag;
    }

    public function edit($id, TagForm $form): void
    {
        $tag = $this->repository->get($id);
        $tag->edit($form->name, Inflector::slug($form->slug));

        $this->repository->save($tag);
    }

    public function remove($id): void
    {
        $tag = $this->repository->get($id);
        $this->repository->remove($tag);
    }
}
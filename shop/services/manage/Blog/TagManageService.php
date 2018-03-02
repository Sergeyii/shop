<?php

namespace shop\services\manage\Blog;

use shop\entities\Blog\Tag;
use shop\forms\manage\Blog\TagForm;
use shop\repositories\Blog\TagRepository;
use yii\helpers\Inflector;

class TagManageService
{
    private $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    public function get($id): ?Tag
    {
        return $this->tags->get($id);
    }

    public function create(TagForm $form): Tag
    {
        $tag = Tag::create($form->name, Inflector::slug($form->slug));
        $this->tags->save($tag);
        return $tag;
    }

    public function edit($id, TagForm $form): void
    {
        $tag = $this->get($id);
        $tag->edit($form->name, Inflector::slug($form->slug));
        $this->tags->save($tag);
    }

    public function remove($id): void
    {
        $tag = $this->get($id);
        $this->tags->remove($tag);
    }
}
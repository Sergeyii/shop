<?php

namespace shop\services\manage\Blog;

use shop\entities\Blog\Post\Post;
use shop\entities\Blog\Tag;
use shop\entities\Meta;
use shop\forms\manage\Blog\Post\PostForm;
use shop\repositories\Blog\CategoryRepository;
use shop\repositories\Blog\PostRepository;
use shop\repositories\Blog\TagRepository;
use shop\services\TransactionManager;
use yii\helpers\Inflector;

class PostManageService
{
    private $repository;
    private $tags;
    private $categories;
    private $transaction;

    public function __construct(
        PostRepository $repository,
        CategoryRepository $categories,
        TagRepository $tags,
        TransactionManager $transaction
    )
    {
        $this->repository = $repository;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->transaction = $transaction;
    }

    public function get($id)
    {
        return $this->repository->get($id);
    }

    public function create(PostForm $form): Post
    {
        $category = $this->categories->get($form->categoryId);

        $post = Post::create(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->keywords,
                $form->meta->description
            )
        );

        if($form->photo){
            $post->setPhoto($form->photo);
        }

        foreach($form->tags->existing as $tagId){
            $tag = $this->tags->get($tagId);
            $post->assignTag($tag);
        }

        $this->transaction->wrap(function() use ($post, $form){
            $this->createTagFromNewNames($form->tags->newNames, $post);
            $this->repository->save($post);
        });

        return $post;
    }

    public function createTagFromNewNames(array $newNames, Post $post): void
    {
        foreach($newNames as $tagName){
            if( !($tag = $this->tags->findByName($tagName)) ){
                $tag = Tag::create($tagName, Inflector::slug($tagName));
                $this->tags->save($tag);
                $post->assignTag($tag);
            }
        }
    }

    public function edit($id, PostForm $form): void
    {
        $post = $this->repository->get($id);
        $category = $this->categories->get($form->categoryId);

        $post->edit(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->keywords,
                $form->meta->description
            )
        );

        if($form->photo){
            $post->setPhoto($form->photo);
        }

        $this->transaction->wrap(function() use($post, $form){
            $post->revokeTags();
            $this->repository->save($post);

            foreach($form->tags->existing as $tagId){
                $tag = $this->tags->get($tagId);
                $post->assignTag($tag);
            }

            $this->createTagFromNewNames($form->tags->newNames, $post);
            $this->repository->save($post);
        });
    }

    public function removePhoto($postId): void
    {
        $post = $this->get($postId);
        $post->removePhoto();
        $this->repository->save($post);
    }

    public function remove($id): void
    {
        $post = $this->repository->get($id);
        $this->repository->remove($post);
    }

    public function draft($id): void
    {
        $post = $this->repository->get($id);
        $post->draft();
        $this->repository->save($post);
    }

    public function activate($id): void
    {
        $post = $this->repository->get($id);
        $post->activate();
        $this->repository->save($post);
    }
}
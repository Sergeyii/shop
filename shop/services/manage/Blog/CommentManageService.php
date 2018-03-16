<?php

namespace shop\services\manage\Blog;

use shop\entities\Blog\Post\Comment;
use shop\forms\manage\Blog\Post\CommentEditForm;
use shop\repositories\Blog\PostRepository;

class CommentManageService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function get($postId, $id): Comment
    {
        $post = $this->posts->get($postId);
        return $post->getComment($id);
    }

    public function edit($postId, $id, CommentEditForm $form): void
    {
        $post = $this->posts->get($postId);
        $post->editComment($id, $form->parentId, $form->text);
        $this->posts->save($post);
    }

    public function activate($postId, $id): void
    {
        $post = $this->posts->get($postId);
        $post->activateComment($id);
        $this->posts->save($post);
    }

    public function remove($post_id, $id): void
    {
        $post = $this->posts->get($post_id);
        $post->removeComment($id);
        $this->posts->save($post);
    }
}
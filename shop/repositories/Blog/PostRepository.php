<?php

namespace shop\repositories\Blog;

use shop\entities\Blog\Post\Post;
use shop\repositories\NotFoundException;
use RuntimeException;

class PostRepository
{
    public function get($id): Post
    {
        if( !($post = Post::findOne($id)) ){
            throw new NotFoundException('Post not found!');
        }
        return $post;
    }

    public function existsByCategory($id): bool
    {
        return Post::find()->andWhere(['category_id' => $id])->exists();
    }

    public function save(Post $post)
    {
        if( !$post->save() ){
            throw new RuntimeException('Saving error!');
        }
    }

    public function remove(Post $post)
    {
        if( !$post->delete() ){
            throw new RuntimeException('Removing error.');
        }
    }
}
<?php

namespace shop\entities\Blog\Post;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "blog_comments".
 *
 * @property integer $id
 * @property integer $post_id
 * @property integer $user_id
 * @property integer $parent_id
 * @property integer $created_at
 * @property string $text
 * @property integer $active
 *
 * @property Post $post
 */
class Comment extends ActiveRecord
{
    public static function create($userId, $parentId, $text): self
    {
        $review = new static();
        $review->user_id = $userId;
        $review->parent_id = $parentId;
        $review->text = $text;
        $review->created_at = time();
        $review->active = true;
        return $review;
    }

    public function edit($parentId, $text): void
    {
        $this->parent_id = $parentId;
        $this->text = $text;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function draft(): void
    {
        $this->active = false;
    }

    public function isActive(): bool
    {
        return $this->active == true;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public function isChildOf($id): bool
    {
        return $this->parent_id == $id;
    }

    public function getPost(): ActiveQuery
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    public static function tableName(): string
    {
        return 'blog_comments';
    }
}
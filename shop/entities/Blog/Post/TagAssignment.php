<?php

namespace shop\entities\Blog\Post;

use yii\db\ActiveRecord;

/**
 * @property integer $tag_id
 * @property integer $post_id
 */

class TagAssignment extends ActiveRecord
{
    public static function create($id): self
    {
        $model = new static();
        $model->tag_id = $id;
        return $model;
    }

    public static function tableName()
    {
        return 'blog_tag_assignments';
    }

    public function isForTag($id): bool
    {
        return $this->tag_id == $id;
    }
}
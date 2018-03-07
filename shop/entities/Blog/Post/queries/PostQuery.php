<?php

namespace shop\entities\Blog\Post\queries;

use shop\entities\Blog\Post\Post;
use yii\db\ActiveQuery;

class PostQuery extends ActiveQuery
{
    public function active($alias = null): PostQuery
    {
        return $this->andWhere([
            ($alias ? $alias.'.' : '').'status' => Post::STATUS_ACTIVE,
        ]);
    }
}
<?php

namespace shop\entities\Blog;
use shop\entities\behaviors\MaxValueBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;

/**
 * This is the model class for table "blog_categories".
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property integer $sort
 * @property string $meta_json
 * @property Meta $meta
 */
class Category extends \yii\db\ActiveRecord
{
    public $meta;

    public function behaviors()
    {
        return [
            MetaBehavior::class,
        ];
    }

    public static function create($name, $slug, $title, $description, $sort, Meta $meta): self
    {
        $category = new static();
        $category->name = $name;
        $category->slug = $slug;
        $category->title = $title;
        $category->description = $description;
        $category->meta = $meta;
        $category->sort = $sort;
        return $category;
    }

    public function edit($name, $slug, $title, $description, $sort, Meta $meta): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->title = $title;
        $this->description = $description;
        $this->meta = $meta;
        $this->sort = $sort;
    }

    public static function getSortMax()
    {
        return Category::find()->max('sort')+1;
    }

    public static function tableName()
    {
        return 'blog_categories';
    }
}

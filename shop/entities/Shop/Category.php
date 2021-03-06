<?php

namespace shop\entities\Shop;

use paulzi\nestedsets\NestedSetsBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\Shop\queries\CategoryQuery;
use yii\db\ActiveRecord;

/**
 * @property integer id
 * @property string name
 * @property string slug
 * @property string title
 * @property string description
 * @property Meta meta
 * @property Category parent
 * @property Category[] $children
 * @mixin NestedSetsBehavior
 * */

class Category extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, $title, $description, Meta $meta): self
    {
        $category = new static();
        $category->name = $name;
        $category->slug = $slug;
        $category->title = $title;
        $category->description = $description;

        $category->meta = $meta;

        return $category;
    }

    public function edit($name, $slug, $title, $description, Meta $meta): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->title = $title;
        $this->description = $description;

        $this->meta = $meta;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->title;
    }

    public function getHeadingTitle(): string
    {
        return $this->title ?: $this->name;
    }

    public function behaviors()
    {
        return [
            MetaBehavior::className(),
            NestedSetsBehavior::className(),
        ];
    }

    public static function tableName()
    {
        return '{{%shop_categories%}}';
    }

    //Указываем что необходимо оборачивать все операции в транзакцию
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(static::class);
    }
}
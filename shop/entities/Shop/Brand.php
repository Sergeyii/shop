<?php

namespace shop\entities\Shop;

use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use yii\db\ActiveRecord;

/**
 * @property string $name
 * @property string $slug
 * @property Meta $meta
 * */

class Brand extends ActiveRecord
{
    public $meta;

    public static function create(string $name, string $slug, $meta): self
    {
        $brand = new static();
        $brand->name = $name;
        $brand->slug = $slug;
        $brand->meta = $meta;

        return $brand;
    }

    public function edit(string $name, string $slug, $meta): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->meta = $meta;
    }

    public static function tableName(): string
    {
        return '{{%shop_brands}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class
        ];
    }
}
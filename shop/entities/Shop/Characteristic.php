<?php

namespace shop\entities\Shop;

use shop\entities\behaviors\JsonParamBehavior;
use yii\db\ActiveRecord;

/**
 * @property string $name
 * @property integer $type
 * @property string $required
 * @property string $default
 * @property array $variants
 * @property integer $sort
 * */

class Characteristic extends ActiveRecord
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';

    public $variants;

    public static function tableName(): string
    {
        return '{{%shop_categories}}';
    }

    public static function create($name, $type, $required, $default, array $variants, $sort): self
    {
        $category = new static();
        $category->name = $name;
        $category->type = $type;
        $category->required = $required;
        $category->default = $default;
        $category->variants = $variants;
        $category->sort = $sort;

        return $category;
    }

    public function edit($name, $type, $required, $default, array $variants, $sort): void
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->default = $default;
        $this->variants = $variants;
        $this->sort = $sort;
    }

    public function isSelect(): bool
    {
        return count($this->variants) > 0;
    }

   public function behaviors()
   {
       return [
           'JsonParamBehavior' => [
                'class' => JsonParamBehavior::class,
                'attribute' => 'variants',
                'dbAttribute' => 'variants_json',
            ]
       ];
   }
}
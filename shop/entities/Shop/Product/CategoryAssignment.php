<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property integer $category_id
 * @property integer $product_id
 */
class CategoryAssignment extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_category_assignments}}';
    }

    public function isForCategory($id): bool
    {
        return $this->category_id == $id;
    }

    public static function create($categoryId): self
    {
        $assignment = new static();
        $assignment->category_id = $categoryId;

        return $assignment;
    }
}
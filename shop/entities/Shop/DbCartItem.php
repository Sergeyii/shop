<?php

namespace shop\entities\Shop;

use shop\entities\Shop\Product\Product;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $modification_id
 * @property string $user_hash
 * @property integer $quantity
 * @property Product $product
*/
class DbCartItem extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'shop_cart_items';
    }

    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
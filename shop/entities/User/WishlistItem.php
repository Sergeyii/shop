<?php

namespace shop\entities\User;

/**
 * This is the model class for table "user_wishlist_items".
 *
 * @property integer $user_id
 * @property integer $product_id
 *
 * @property ShopProducts $product
 * @property Users $user
 */
class WishlistItem extends \yii\db\ActiveRecord
{
    public static function create($userId, $productId): self
    {
        $self = new static();
        $self->user_id = $userId;
        $self->product_id = $productId;

        return $self;
    }

    public static function tableName()
    {
        return 'user_wishlist_items';
    }

    public function isForProduct($productId): bool
    {
        return $this->product_id == $productId;
    }
}

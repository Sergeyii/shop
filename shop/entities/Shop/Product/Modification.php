<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $price
 * @property int $quantity
*/

class Modification extends ActiveRecord
{
    public static function create($code, $name, $price): self
    {
        $object = new static();
        $object->code = $code;
        $object->name = $name;
        $object->price = $price;

        return $object;
    }

    public function edit($code, $name, $price, $quantity): void
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function checkout($quantity): void
    {
        if($quantity > $this->quantity){
            throw new \DomainException('Only '.$this->quantity.' items are available.');
        }

        $this->quantity -= $quantity;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public function isCodeEqualTo($code): bool
    {
        return $this->code == $code;
    }

    public static function tableName()
    {
        return '{{%shop_modifications}}';
    }
}
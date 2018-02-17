<?php

namespace shop\entities\Shop;

use shop\entities\Shop\queries\DiscountQuery;
use yii\db\ActiveRecord;

/* @property integer $percent */
/* @property string name */
/* @property string $from_date */
/* @property string $to_date */
/* @property boolean $active */

class Discount extends ActiveRecord
{
    public static function create($percent, $name, $fromDate, $toDate, $sort): self
    {
        $discount = new static();
        $discount->percent = $percent;
        $discount->name = $name;
        $discount->from_date = $fromDate;
        $discount->to_date = $toDate;
        $discount->sort = $sort;
        $discount->activate();

        return $discount;
    }

    public function edit($percent, $name, $fromDate, $toDate, $sort)
    {
        $this->percent = $percent;
        $this->name = $name;
        $this->from_date = $fromDate;
        $this->to_date = $toDate;
        $this->sort = $sort;
    }

    public function activate(): bool
    {
        $this->active = true;
    }

    public function draft(): bool
    {
        $this->active = false;
    }

    public function isEnabled(): bool
    {
        return $this->active == true;
    }

    public static function tableName()
    {
        return '{{%shop_discounts}}';
    }

    public static function find(): DiscountQuery
    {
        return new DiscountQuery(static::class);
    }
}
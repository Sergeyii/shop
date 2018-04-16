<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Product\Product;
use yii\base\Model;

class PriceForm extends Model
{
    public $new;
    public $old;

    public function __construct(Product $product = null, array $config = [])
    {
        if($product){
            $this->old = $product->price_old;
            $this->new = $product->price_new;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['new', 'required'],
            [['old', 'new'], 'integer', 'min' => 0],
        ];
    }
}
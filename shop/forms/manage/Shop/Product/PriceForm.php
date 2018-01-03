<?php

namespace shop\forms\manage\Shop\Product;

use yii\base\Model;

class PriceForm extends Model
{
    public $new;
    public $old;

    public function __construct(Product $product = null, array $config = [])
    {
        if($product){
            $this->old = $product->old;
            $this->new = $product->new;
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
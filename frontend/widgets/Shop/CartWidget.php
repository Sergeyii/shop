<?php

namespace frontend\widgets\Shop;

use shop\cart\Cart;
use yii\base\Widget;

class CartWidget extends Widget
{
    private $cart;

    public function __construct(Cart $cart, array $config = [])
    {
        $this->cart = $cart;

        parent::__construct($config);
    }

    public function run()
    {
        return $this->render('cart', [
            'cart' => $this->cart,
        ]);
    }
}
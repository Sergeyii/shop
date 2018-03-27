<?php

namespace api\formatters;

use shop\entities\Shop\Product\Product;
use yii\helpers\Url;

class WishlistProductListFormatter implements ApiFormatterInterface
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function format(): array
    {
        return [
            'id' => $this->product->id,
            'code' => $this->product->code,
            'name' => $this->product->name,
            'price' => [
                'new' => $this->product->price_new,
                'old' => $this->product->price_old,
            ],
            'thumbnail' => $this->product->mainPhoto ? $this->product->mainPhoto->getThumbFileUrl('file', 'cart_list') : null,
            '_links' => [
                'self' => ['href' => Url::to(['shop/product/view', 'id' => $this->product->id], true)],
                'cart' => ['href' => Url::to(['/shop/cart/add', 'id' => $this->product->id], true)],
            ],
        ];
    }
}
<?php

namespace api\formatters;

use yii\base\Model;
use yii\helpers\Url;
use shop\entities\Shop\Product\Product;

class ProductListFormatter implements ApiFormatterInterface
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
            'category' => [
                'id' => $this->product->category->id,
                'name' => $this->product->category->name,
                '_links' => [
                    'self' => ['href' => Url::to(['category', 'id' => $this->product->category->id], true)],
                ],
            ],
            'brand' => [
                'id' => $this->product->brand->id,
                'name' => $this->product->brand->name,
                '_links' => [
                    'self' => ['href' => Url::to(['brand', 'id' => $this->product->brand->id], true)]
                ],
            ],
            'price' => [
                'new' => $this->product->price_new,
                'old' => $this->product->price_old,
            ],
            'thumbnail' => $this->product->mainPhoto ? $this->product->mainPhoto->getThumbFileUrl('file', 'catalog_list') : null,
            '_links' => [
                'self' => ['href' => Url::to(['view', 'id' => $this->product->id], true)],
                'wish' => ['href' => Url::to(['/shop/wishlist/add', 'id' => $this->product->id], true)],
                'cart' => ['href' => Url::to(['/shop/cart/add', 'id' => $this->product->id], true)],
            ],
        ];
    }
}
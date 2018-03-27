<?php

namespace api\formatters;

use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Photo;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Tag;
use yii\helpers\Url;

class ProductViewFormatter
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
            'description' => $this->product->description,
            'categories' => [
                'main' => [
                    'id' => $this->product->category->id,
                    'name' => $this->product->category->name,
                    '_links' => [
                        'self' => ['href' => Url::to(['category', 'id' => $this->product->category->id], true)],
                    ],
                ],
                'other' => array_map(function(Category $category){
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        '_links' => [
                            'self' => ['href' => Url::to(['category', 'id' => $category->id], true)],
                        ],
                    ];
                }, $this->product->categories),
            ],
            'brand' => [
                'id' => $this->product->brand->id,
                'name' => $this->product->brand->name,
                '_links' => [
                    'self' => ['href' => Url::to(['brand', 'id' => $this->product->brand->id], true)]
                ],
            ],
            'tags' => array_map(function(Tag $tag){
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    '_links' => [
                        'self' => ['href' => Url::to(['tag', 'id' => $tag->id], true)],
                    ],
                ];
            }, $this->product->tags),
            'price' => [
                'new' => $this->product->price_new,
                'old' => $this->product->price_old,
            ],
            'photos' => array_map(function(Photo $photo){
                return [
                    'thumbnail' => $photo->getThumbFileUrl('file', 'catalog_list'),
                    'origin' => $photo->getThumbFileUrl('file', 'catalog_origin'),
                ];
            }, $this->product->photos),
            'modifications' => array_map(function(Modification $modification){
                return [
                    'id' => $modification->id,
                    'code' => $modification->code,
                    'name' => $modification->name,
                    'price' => $this->product->getModificationPrice($modification->id),
                ];
            }, $this->product->modifications),
            'rating' => $this->product->rating,
            'weight' => $this->product->weight,
            'quantity' => $this->product->quantity,
            '_links' => [
                'self' => ['href' => Url::to(['view', 'id' => $this->product->id], true)],
                'wish' => ['href' => Url::to(['/shop/wishlist/add', 'id' => $this->product->id], true)],
                'cart' => ['href' => Url::to(['/shop/cart/add', 'id' => $this->product->id], true)],
            ],
        ];
    }
}
<?php

namespace shop\services\search;

use Elasticsearch\Client;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Product\Value;
use yii\helpers\ArrayHelper;

class ProductIndexer
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function clear(): void
    {
        $this->client->deleteByQuery([
            'index' => 'shop',
            'type' => 'products',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    public function index(Product $product): void
    {
        $this->client->index([
            'index' => 'shop',
            'type' => 'products',
            'id' => $product->id,
            'body' => [
                'name' => $product->name,
                'id' => $product->id,
                'category_id' => $product->category_id,
                'brand' => $product->brand_id,
                'price_new' => $product->price_new,
                'price_old' => $product->price_old,
                'description' => strip_tags($product->description),
                'categories' => ArrayHelper::merge(
                    [$product->category->id],
                    ArrayHelper::getColumn($product->category->parents, 'id'),
                    ArrayHelper::getColumn($product->categories, 'id'),
                    array_reduce(array_map(function (Category $category) {
                        return ArrayHelper::getColumn($category->parents, 'id');
                    }, $product->categories), 'array_merge', [])
                ),
                'values' => ArrayHelper::map($product->values,
                    function(Value $value){
                        return 'attr_'.$value->characteristic_id;
                    },
                    function(Value $value){
                        return [
                            'characteristic' => $value->characteristic_id,
                            'value_string' => (string)$value->value,
                            'value_int' => (int)$value->value,
                        ];
                    }
                ),
            ],
        ]);
    }

    public function remove(Product $product): void
    {
        $this->client->delete([
            'index' => 'shop',
            'type' => 'products',
            'id' => $product->id,
        ]);
    }
}
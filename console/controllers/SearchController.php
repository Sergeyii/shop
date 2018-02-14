<?php

namespace console\controllers;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Product\Value;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class SearchController extends Controller
{
    public $client;

    public function __construct(string $id, $module, Client $client, array $config = [])
    {
        $this->client = $client;
        parent::__construct($id, $module, $config);
    }

    public function actionReindexNew()
    {
        $query = Product::find()
            ->active()
            ->with(['category', 'categoryAssignments', 'tagAssignments', 'values'])
            ->orderBy('id');

        $this->stdout('Clearing'.PHP_EOL);

        //Удаляем старый индекс
        try{
            $this->client->indices()->delete(['index' => 'shop']);
        }catch(Missing404Exception $e){
            $this->stdout('Index is empty'.PHP_EOL);
        }


        //Добавляем товары в индекс
        $this->stdout('Indexing of products'.PHP_EOL);

        //Указываем как необходимо обрабатывать поля
        $this->client->create([
            'index' => 'shop',
            'type' => 'products',
            'id' => 'products_id',
            'body' => [
                'mappings' => [
                    'products' => [
                        '_source' => [
                            'enabled' => true,
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'text',
                            ],
                            'name' => [
                                'type' => 'text',
                            ],
                            'category_id' => [
                                'type' => 'integer',
                            ],
                            'brand' => [
                                'type' => 'integer',
                            ],
                            'price_new' => [
                                'type' => 'float',
                            ],
                            'price_old' => [
                                'type' => 'float',
                            ],
                            'description' => [
                                'type' => 'text',
                            ],
                            'categories' => [
                                'type' => 'integer',
                            ],
                            'values' => [
                                'type' => 'nested',
                                'properties' => [
                                    'characteristic' => [
                                        'type' => 'integer',
                                    ],
                                    'value_string' => [
                                        'type' => 'keyword',
                                    ],
                                    'value_int' => [
                                        'type' => 'text',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        /* @var Product $product */
        foreach($query->each() as $product){
            $this->stdout('Product #'.$product->id.PHP_EOL);

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

    }

    public function actionReindex()
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category');

        try{
            $this->client->indices()->delete(['index' => 'shop']);
        }catch(Missing404Exception $e){
            $this->stdout('Index is missing'. PHP_EOL);
        }

        /* @var Product $product */
        foreach($query->each() as $product){
            $this->stdout('Product #'.$product->id.PHP_EOL);

            $response = $this->client->index([
                'index' => 'shop',
                'type' => 'product',
                'id' => $product->id,
                'body' => [
                    'name' => $product->name,
                    'id' => $product->id,
                    'category_id' => $product->category_id,
                    'brand' => $product->brand_id,
                    'price_new' => $product->price_new,
                    'price_old' => $product->price_old,
                    'description' => strip_tags($product->description),
                    'categories' => ArrayHelper::merge([$product->category_id], ArrayHelper::getColumn($product->categoryAssignments, 'category_id')),
                    'values' => ArrayHelper::map($product->values,
                        function(Value $value){
                            return 'attr_'.$value->characteristic_id;
                        },
                        function(Value $value){
                            return [
                                'value_string' => (string)$value->value,
                                'value_int' => (int)$value->value,
                            ];
                        }),

                ]
            ]);
        }

        $this->stdout('Reindex action working!');
    }
}
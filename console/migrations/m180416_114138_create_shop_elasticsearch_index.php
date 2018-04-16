<?php

use yii\db\Migration;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class m180416_114138_create_shop_elasticsearch_index extends Migration
{
    public function up()
    {
        $client = $this->getClient();

        //Удаляем старый индекс
        try{
            $client->indices()->delete(['index' => 'shop']);
        }catch(Missing404Exception $e){}

        //Указываем как необходимо обрабатывать поля
        $client->indices()->create([
            'index' => 'shop',
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
    }

    public function down()
    {
        try{
            $this->getClient()->indices()->delete(['index' => 'shop']);
        }catch(Missing404Exception $e){}
    }

    private function getClient(): Client
    {
        return Yii::$container->get(Client::class);
    }
}
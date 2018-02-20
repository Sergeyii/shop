<?php

namespace shop\cart\storage;

use shop\cart\CartItem;
use shop\entities\Shop\Product\Product;
use yii\helpers\Json;
use yii\web\Cookie;
use Yii;

class CookieStorage implements StorageInterface
{
    private $key;
    private $timeout;

    public function __construct($key, int $timeout=0)
    {
        $this->key = $key;
        $this->timeout = $timeout;
    }

    public function load(): array
    {
        if($cookie = Yii::$app->request->cookies){
            return array_filter(
                array_map(function(array $row){
                    if(isset($row['p'], $row['q'])){
                        $product = Product::find()->active()->andWhere(['id' => $row['p']])->one();

                        return new CartItem($product, $row['m'], $row['q']);
                    }
                    return false;
                }, Json::decode($cookie->getValue($this->key, '')) ?? [])
            );
        }
        return [];
    }

    public function save(array $items): void
    {
        Yii::$app->response->cookies->add(new Cookie([
            'expire' => time() + $this->timeout,
            'name' => $this->key,
            'value' => Json::encode(array_map(function(CartItem $item){
                return [
                    'p' => $item->getProductId(),
                    'm' => $item->getModificationId(),
                    'q' => $item->getQuantity(),
                ];
            }, $items)),
        ]));
    }
}
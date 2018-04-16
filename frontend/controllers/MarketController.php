<?php

namespace frontend\controllers;

use yii\caching\TagDependency;
use yii\helpers\Url;
use shop\entities\Shop\Product\Product;
use shop\services\yandex\YandexMarket;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class MarketController extends Controller
{
    private $generator;

    public function __construct(string $id, $module, YandexMarket $generator, array $config = [])
    {
        $this->generator = $generator;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): Response
    {
        $xml = Yii::$app->cache->getOrSet('yandex-market', function(){
            return $this->generator->generate(function(Product $product){
                return Url::to(['/shop/catalog/product', 'id' => $product->id], true);
            });
        }, 3600, new TagDependency(['tags' => ['categories']]));

        return Yii::$app->response->sendContentAsFile($xml, 'yandex-market.xml', [
            'mimeType' => 'application/xml',
            'inline' => true,
        ]);
    }
}
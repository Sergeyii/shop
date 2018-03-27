<?php

namespace api\controllers\shop;

use api\formatters\WishlistProductListFormatter;
use api\providers\MapDataProvider;
use shop\readModels\Shop\ProductReadRepository;
use shop\services\cabinet\WishlistService;
use yii\data\DataProviderInterface;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use Yii;

class WishlistController extends Controller
{
    private $service;
    private $products;

    public function __construct(
        string $id,
        $module,
        WishlistService $service,
        ProductReadRepository $products,
        array $config = []
    )
    {
        $this->service = $service;
        $this->products = $products;
        parent::__construct($id, $module, $config);
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'add' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionIndex(): DataProviderInterface
    {
        $dataProvider = $this->products->getWishList(Yii::$app->user->id);
        return new MapDataProvider($dataProvider, WishlistProductListFormatter::class);
    }

    public function actionAdd($id)
    {
        try{
            $this->service->add(Yii::$app->user->id, $id);
            Yii::$app->response->setStatusCode(201);
        }catch(\DomainException $e){
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }

    public function actionDelete($id)
    {
        try{
            $this->service->remove(Yii::$app->user->id, $id);
            Yii::$app->response->setStatusCode(204);
        }catch(\DomainException $e){
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }
}
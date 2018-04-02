<?php

namespace api\controllers\shop;

use api\formatters\WishlistProductListFormatter;
use api\providers\MapDataProvider;
use shop\readModels\Shop\ProductReadRepository;
use shop\useCases\cabinet\WishlistService;
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

    /**
     * @SWG\Get(
     *     path="/shop/wishlist",
     *     tags={"WishList"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/WishlistItem")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function actionIndex(): DataProviderInterface
    {
        $dataProvider = $this->products->getWishList(Yii::$app->user->id);
        return new MapDataProvider($dataProvider, WishlistProductListFormatter::class);
    }

    /**
     * @SWG\Post(
     *     path="/shop/products/{productId}/wish",
     *     tags={"WishList"},
     *     @SWG\Parameter(name="productId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=201,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     */
    public function actionAdd($id)
    {
        try{
            $this->service->add(Yii::$app->user->id, $id);
            Yii::$app->response->setStatusCode(201);
        }catch(\DomainException $e){
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/shop/wishlist/{id}",
     *     tags={"WishList"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     */
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

/**
 * @SWG\Definition(
 *     definition="WishlistItem",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="code", type="string"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="price", type="object",
 *         @SWG\Property(property="new", type="integer"),
 *         @SWG\Property(property="old", type="integer"),
 *     ),
 *     @SWG\Property(property="thumbnail", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *         @SWG\Property(property="cart", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 */
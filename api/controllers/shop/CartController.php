<?php

namespace api\controllers\shop;

use api\formatters\CartFormatter;
use shop\forms\Shop\AddToCartForm;
use shop\readModels\Shop\ProductReadRepository;
use shop\services\Shop\CartService;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class CartController extends Controller
{
    private $service;
    private $products;

    public function __construct(string $id, $module, CartService $service, ProductReadRepository $products, array $config = [])
    {
        $this->service = $service;
        $this->products = $products;
        parent::__construct($id, $module, $config);
    }

    protected function verbs(): array
    {
        return [
            'index' => ['GET'],
            'add' => ['POST'],
            'quantity' => ['POST'],
            'delete' => ['DELETE'],
            'clear' => ['DELETE'],
        ];
    }

    /**
     * @SWG\Get(
     *     path="/shop/cart",
     *     tags={"Cart"},
     *     @SWG\Response(
     *          response=200,
     *          description="Success response",
     *          @SWG\Schema(ref="#/definitions/Cart"),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * */
    public function actionIndex(): array
    {
        return (new CartFormatter($this->service->getCart()))->format();
    }

    /**
     * @SWG\Post(
     *     path="shop/products/{productId}/cart",
     *     tags={"Cart"},
     *     @SWG\Parameter(name="productId", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="modification", in="formData", required=false, type="integer"),
     *     @SWG\Parameter(name="quantity", in="formData", required=true, type="integer"),
     *     @SWG\Response(
     *          response=201,
     *          description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @return array|AddToCartForm
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * */
    public function actionAdd($id)
    {
        if( !$product = $this->products->find($id) ){
            throw new NotFoundHttpException('The requested page not found.');
        }

        $form = new AddToCartForm($product);
        $form->load(Yii::$app->request->getBodyParams(), '');

        if($form->validate()){
            try{
                $this->service->add($product->id, $form->modification, $form->quantity);
                Yii::$app->response->setStatusCode(201);
                return [];
            }catch(\DomainException $e){
                throw new BadRequestHttpException($e->getMessage(), null, $e);
            }
        }
        return $form;
    }

    /**
     * @SWG\POST(
     *     path="shop/cart/{productId}/quantity",
     *     tags={"Cart"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="string"),
     *     @SWG\Parameter(name="quantity", in="formData", required=true, type="integer"),
     *     @SWG\Response(
     *          response=201,
     *          description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * */
    public function actionQuantity($id): void
    {
        try{
            $this->service->set($id, (int)Yii::$app->request->post('quantity'));
        }catch(\DomainException $e){
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }

    /**
     * @SWG\POST(
     *     path="shop/cart/{id}",
     *     tags={"Cart"},
     *     @SWG\Parameter(name="id", in="path", required=true, type="string"),
     *     @SWG\Response(
     *          response=204,
     *          description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @param $id
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * */
    public function actionDelete($id): void
    {
        try{
            $this->service->remove($id);
            Yii::$app->response->setStatusCode(204);
        }catch(\DomainException $e){
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/shop/cart",
     *     tags={"Cart"},
     *     @SWG\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     * @throws BadRequestHttpException
     */
    public function actionClear(): void
    {
        try{
            $this->service->clear();
            Yii::$app->response->setStatusCode(204);
        }catch(\DomainException $e){
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }
}

/**
 * @SWG\Definition(
 *     definition="Cart",
 *     type="object",
 *     @SWG\Property(property="weight", type="integer"),
 *     @SWG\Property(property="amount", type="integer"),
 *     @SWG\Property(property="items", type="array", @SWG\Items(
 *         type="object",
 *         @SWG\Property(property="id", type="string"),
 *         @SWG\Property(property="quantity", type="integer"),
 *         @SWG\Property(property="price", type="integer"),
 *         @SWG\Property(property="cost", type="integer"),
 *         @SWG\Property(property="product", type="object",
 *             @SWG\Property(property="id", type="integer"),
 *             @SWG\Property(property="code", type="string"),
 *             @SWG\Property(property="name", type="string"),
 *             @SWG\Property(property="thumbnail", type="string"),
 *             @SWG\Property(property="_links", type="object",
 *                 @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *             )
 *         ),
 *         @SWG\Property(property="modification", type="object",
 *             @SWG\Property(property="id", type="integer"),
 *             @SWG\Property(property="code", type="string"),
 *             @SWG\Property(property="name", type="string"),
 *             @SWG\Property(property="_links", type="object",
 *                 @SWG\Property(property="quantity", type="object", @SWG\Property(property="href", type="string")),
 *             )
 *         )
 *     )),
 *     @SWG\Property(property="cost", type="object",
 *         @SWG\Property(property="origin", type="integer"),
 *         @SWG\Property(property="discounts", type="array", @SWG\Items(
 *             type="object",
 *             @SWG\Property(property="name", type="string"),
 *             @SWG\Property(property="value", type="integer")
 *         )),
 *         @SWG\Property(property="total", type="integer"),
 *     ),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     )
 * )
 */
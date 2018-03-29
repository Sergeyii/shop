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

    public function actionIndex(): array
    {
        return (new CartFormatter($this->service->getCart()))->format();
    }

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

    public function actionQuantity($id): void
    {
        try{
            $this->service->set($id, (int)Yii::$app->request->post('quantity'));
        }catch(\DomainException $e){
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }

    public function actionDelete($id): void
    {
        try{
            $this->service->remove($id);
            Yii::$app->response->setStatusCode(204);
        }catch(\DomainException $e){
            throw new BadRequestHttpException($e->getMessage(), null, $e);
        }
    }

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
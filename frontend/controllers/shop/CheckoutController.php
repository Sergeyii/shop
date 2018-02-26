<?php

namespace frontend\controllers\shop;

use shop\cart\Cart;
use shop\forms\Shop\Order\OrderForm;
use shop\services\Shop\OrderService;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class CheckoutController extends Controller
{
    public $layout = 'blank';

    private $cart;
    private $service;

    public function __construct(string $id, $module, Cart $cart, OrderService $service, array $config = [])
    {
        $this->cart = $cart;
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $form = new OrderForm($this->cart->getWeight());

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                //Создать заказ
                $order = $this->service->checkout(Yii::$app->user->id, $form);
                return $this->redirect(['/cabinet/order/view', 'id' => $order->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('index', [
            'cart' => $this->cart,
            'model' => $form,
        ]);
    }
}
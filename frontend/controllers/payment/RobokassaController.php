<?php

namespace frontend\controllers\payment;

use robokassa\FailAction;
use robokassa\Merchant;
use robokassa\ResultAction;
use robokassa\SuccessAction;
use shop\entities\Shop\Order\Order;
use shop\readModels\Shop\OrderReadRepository;
use shop\useCases\Shop\OrderService;
use yii\web\Controller;
use Yii;

class RobokassaController extends Controller
{
    public $enableCsrfValidation = false;

    private $orders;
    private $service;

    public function __construct(string $id, $module, OrderReadRepository $orders, OrderService $service, array $config = [])
    {
        $this->orders = $orders;
        $this->service = $service;

        parent::__construct($id, $module, $config);
    }

    //Когда человек хочет оплатить
    public function actionInvoice($id)
    {
        $order = $this->loadModel($id);
        $this->service->setPayingStatus($order->id);

        return $this->getMerchant()->payment(
            $order->cost,
            $order->id,
            'Оплата заказа с сайта '.Yii::$app->request->hostName,
            null,
            $order->user->email
        );
    }

    private function loadModel($id): Order
    {
        return $this->orders->findOwn(Yii::$app->user->id, $id);
    }

    public function getMerchant(): Merchant
    {
        return Yii::$app->get('robokassa');
    }

    public function actions()
    {
        return [
            'result' => [
                'class' => ResultAction::class,
                'callback' => [$this, 'resultCallback'],
            ],
            'success' => [
                'class' => SuccessAction::class,
                'callback' => [$this, 'successCallback'],
            ],
            'fail' => [
                'class' => FailAction::class,
                'callback' => [$this, 'failCallback'],
            ],
        ];
    }

    public function successCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        //Успешная оплата
        return $this->goBack();
    }
    public function resultCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        try{
            $order = $this->loadModel($nInvId);
            $this->service->pay($order->id);
            return 'OK' . $nInvId;
        }catch(\DomainException $e){
            return $e->getMessage();
        }
    }
    public function failCallback($merchant, $nInvId, $nOutSum, $shp)
    {
        try{
            $order = $this->loadModel($nInvId);
            $this->service->failPaying($order->id);
            return 'Ok';
        }catch(\DomainException $e){
            return $e->getMessage();
        }
    }
}
<?php

namespace frontend\controllers\cabinet;

use Yii;
use shop\useCases\auth\NetworkService;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

class NetworkController extends Controller
{
    public $layout = 'cabinet';

    private $service;

    public function __construct($id, $module, array $config = [], NetworkService $service)
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    public function actions()
    {
        return [
            'attach' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
                'successUrl' => Url::to(['cabinet/default/index']),
            ],
        ];
    }

    public function onAuthSuccess(ClientInterface $client)
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');

        //Прикрепляем соц. сеть к пользователю
        try{
            $this->service->attach(Yii::$app->user->id, $network, $identity);
            Yii::$app->session->setFlash('success', 'Network is successfully attached!');
        }catch(\DomainException $e){
            //Ошибка
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

    }

}
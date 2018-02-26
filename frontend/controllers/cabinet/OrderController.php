<?php

namespace frontend\controllers\cabinet;

use shop\readModels\Shop\OrderReadRepository;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class OrderController extends Controller
{
    public $layout = 'cabinet';
    private $orders;

    public function __construct(string $id, $module, OrderReadRepository $orders, array $config = [])
    {
        $this->orders = $orders;
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
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $dataProvider = $this->orders->getOwn(Yii::$app->user->id);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $order = $this->orders->findOwn(Yii::$app->user->id, $id);

        return $this->render('view', [
            'order' => $order,
        ]);
    }
}
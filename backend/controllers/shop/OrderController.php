<?php

namespace backend\controllers\shop;

use shop\forms\manage\Shop\Order\OrderEditForm;
use shop\services\manage\Shop\OrderManageService;
use Yii;
use backend\forms\Shop\OrderSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class OrderController extends Controller
{
    private $service;

    public function __construct(string $id, $module, OrderManageService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'order' => $this->service->get($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $order = $this->service->get($id);
        $form = new OrderEditForm($order);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($order->id, $form);
                return $this->redirect(['view', 'id' => $order->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'order' => $order,
        ]);
    }

    public function actionDelete($id)
    {
        try{
            $this->service->remove($id);
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }
}

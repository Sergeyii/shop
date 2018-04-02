<?php

namespace backend\controllers\shop;

use shop\forms\manage\Shop\DeliveryMethodForm;
use shop\useCases\manage\Shop\DeliveryMethodManageService;
use Yii;
use backend\forms\Shop\DeliveryMethodSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class DeliveryController extends Controller
{
    private $service;

    public function __construct(string $id, $module, \shop\useCases\manage\Shop\DeliveryMethodManageService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new DeliveryMethodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $method = $this->service->get($id);

        return $this->render('view', [
            'method' => $method,
        ]);
    }

    public function actionCreate()
    {
        $form = new DeliveryMethodForm();

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $method = $this->service->create($form);
                return $this->redirect(['view', 'id' => $method->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $method = $this->service->get($id);
        $form = new DeliveryMethodForm($method);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($method->id, $form);
                return $this->redirect(['view', 'id' => $method->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'method' => $method,
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
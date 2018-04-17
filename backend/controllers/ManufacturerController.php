<?php

namespace backend\controllers;

use shop\forms\manage\ManufacturerForm;
use shop\useCases\manage\ManufacturerManageService;
use Yii;
use backend\forms\ManufacturerSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ManufacturerController extends Controller
{
    private $service;

    public function __construct(string $id, $module, ManufacturerManageService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
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
        $searchModel = new ManufacturerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new ManufacturerForm();

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $model = $this->service->create($form);
                return $this->redirect(['view', 'id' => $model->id]);
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
        $manufacturer = $this->service->get($id);
        $form = new ManufacturerForm($manufacturer);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($manufacturer->id, $form);
                return $this->redirect(['view', 'id' => $manufacturer->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'manufacturer' => $manufacturer,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->service->get($id),
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

<?php

namespace backend\controllers;

use shop\forms\manage\PageForm;
use shop\services\manage\PageManageService;
use Yii;
use backend\forms\PageSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class PageController extends Controller
{
    private $service;

    public function __construct(string $id, $module, PageManageService $service, array $config = [])
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
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new PageForm();

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $page = $this->service->create($form);
                return $this->redirect(['view', 'id' => $page->id]);
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
        $page = $this->service->get($id);
        $form = new PageForm($page);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($page->id, $form);
                return $this->redirect(['view', 'id' => $page->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'page' => $page,
            'model' => $form,
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
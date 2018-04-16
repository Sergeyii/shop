<?php

namespace backend\controllers\Blog;

use shop\forms\manage\Blog\CategoryForm;
use shop\useCases\manage\Blog\CategoryManageService;
use Yii;
use backend\forms\Blog\CategorySearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class CategoryController extends Controller
{
    private $service;

    public function __construct(string $id, $module, CategoryManageService $service, array $config = [])
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
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $dataProvider,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new CategoryForm();

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $category = $this->service->add($form);
                return $this->redirect(['view', 'id' => $category->id]);
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
        $category = $this->service->get($id);
        $form = new CategoryForm($category);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($category->id, $form);
                return $this->redirect(['view', 'id' => $category->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'category' => $category,
            'model' => $form,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'category' => $this->service->get($id),
        ]);
    }

    public function actionDelete($id)
    {
        try{
            $this->service->remove($id);
            return $this->redirect(['index']);
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}
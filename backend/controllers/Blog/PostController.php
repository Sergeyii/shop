<?php

namespace backend\controllers\Blog;

use shop\forms\manage\Blog\Post\PostForm;
use shop\useCases\manage\Blog\PostManageService;
use Yii;
use backend\forms\Blog\PostSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;
use \Exception;

class PostController extends Controller
{
    private $service;

    public function __construct(string $id, $module, PostManageService $service, array $config = [])
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
                    'delete-photo' => ['POST'],
                    'draft' => ['POST'],
                    'activate' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new PostForm();

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $post = $this->service->create($form);
                return $this->redirect(['view', 'id' => $post->id]);
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
        $post = $this->service->get($id);
        $form = new PostForm($post);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($post->id, $form);
                return $this->redirect(['view', 'id' => $post->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        
        return $this->render('update', [
            'post' => $post,
            'model' => $form,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'post' => $this->service->get($id),
        ]);
    }

    public function actionDeletePhoto($id)
    {
        try{
            $this->service->removePhoto($id);
            return $this->redirect(['update', 'id' => $id]);
        }catch(Exception $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    public function actionDraft($id)
    {
        try{
            $this->service->draft($id);
            return $this->redirect(['view', 'id' => $id]);
        }catch(Exception $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    public function actionActivate($id)
    {
        try{
            $this->service->activate($id);
            return $this->redirect(['view', 'id' => $id]);
        }catch(Exception $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    public function actionDelete($id)
    {
        try{
            $this->service->remove($id);
            return $this->redirect(['index']);
        }catch(Exception $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}
<?php

namespace backend\controllers\Blog;

use shop\forms\manage\Blog\TagForm;
use shop\services\manage\Blog\TagManageService;
use Yii;
use backend\forms\Blog\TagSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class TagController extends Controller
{
    private $service;

    public function __construct(string $id, $module, TagManageService $service, array $config = [])
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
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'tag' => $this->service->get($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new TagForm();

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $tag = $this->service->create($form);
                return $this->redirect(['view', 'id' => $tag->id]);
            }catch(\Exception $e){
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
        $tag = $this->service->get($id);
        $form = new TagForm($tag);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($tag->id, $form);
                return $this->redirect(['view', 'id' => $tag->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'tag' => $tag,
            'model' => $form,
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
        $this->redirect(['index']);
    }
}
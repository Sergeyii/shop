<?php

namespace backend\controllers\Blog;

use shop\forms\manage\Blog\Post\CommentEditForm;
use shop\useCases\manage\Blog\CommentManageService;
use shop\useCases\manage\Blog\PostManageService;
use Yii;
use backend\forms\Blog\CommentSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class CommentController extends Controller
{
    private $service;
    private $posts;

    public function __construct(string $id, $module, CommentManageService $service, PostManageService $posts, array $config = [])
    {
        $this->service = $service;
        $this->posts = $posts;
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
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($post_id, $id)
    {
        $comment = $this->service->get($post_id, $id);
        $form = new CommentEditForm($comment);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($post_id, $comment->id, $form);
                return $this->redirect(['view', 'post_id' => $post_id, 'id' => $comment->id]);
            }catch(\Exception $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'comment' => $comment,
        ]);
    }

    public function actionView($post_id, $id)
    {
        $comment = $this->service->get($post_id, $id);
        $post = $this->posts->get($post_id);
        $form = new CommentEditForm($comment);

        return $this->render('view', [
            'model' => $form,
            'post' => $post,
            'comment' => $comment,
        ]);
    }

    public function actionActivate($post_id, $id)
    {
        try{
            $this->service->activate($post_id, $id);
        }catch(\DomainException $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'post_id' => $post_id, 'id' => $id]);
    }

    public function actionDelete($post_id, $id)
    {
        try{
            $this->service->remove($post_id, $id);
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }
}
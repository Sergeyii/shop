<?php

namespace frontend\controllers\blog;

use shop\readModels\Blog\CategoryReadRepository;
use shop\readModels\Blog\PostReadRepository;
use shop\readModels\Blog\TagReadRepository;
use shop\services\Blog\CommentService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PostController extends Controller
{
    public $layout = 'blog';

    private $service;
    private $posts;
    private $categories;
    private $tags;

    public function __construct(
        string $id, $module,
        CommentService $service,
        PostReadRepository $posts,
        CategoryReadRepository $categories,
        TagReadRepository $tags,
        array $config = []
    )
    {
        $this->service = $service;
        $this->posts = $posts;
        $this->categories = $categories;
        $this->tags = $tags;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $dataProvider = $this->posts->getAll();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCategory($slug)
    {
        if( !($category = $this->categories->findBySlug($slug)) ){
            throw new NotFoundHttpException("The requested page doesn't exists");
        }

        $dataProvider = $this->posts->getAllByCategory($category);

        return $this->render('category', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTag($slug)
    {
        if( !($tag = $this->tags->findBySlug($slug)) ){
            throw new NotFoundHttpException("The requested page doesn't found");
        }

        $dataProvider = $this->posts->getAllByTag($tag);

        return $this->render('tag', [
            'tag' => $tag,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPost($id)
    {
        if( !($post = $this->posts->find($id)) ){
            throw new NotFoundHttpException("The requested page doesn't found");
        }

        return $this->render('post', [
            'post' => $post
        ]);
    }
}
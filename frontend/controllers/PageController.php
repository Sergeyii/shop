<?php

namespace frontend\controllers;

use shop\readModels\PageReadRepository;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PageController extends Controller
{
    private $pages;

    public function __construct(string $id, $module, PageReadRepository $pages, array $config = [])
    {
        $this->pages = $pages;
        parent::__construct($id, $module, $config);
    }

    public function actionView($slug)
    {
        if(!$page = $this->pages->findBySlug($slug)){
            throw new NotFoundHttpException("The requested page not found.");
        }

        return $this->render('view', [
            'page' => $page,
        ]);
    }
}
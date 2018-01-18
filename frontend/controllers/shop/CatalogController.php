<?php

namespace frontend\controllers\shop;

class CatalogController extends \yii\web\Controller
{
    public $layout = 'catalog';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCategory($id)
    {
        return $this->render('category');
    }

    public function actionBrand($id)
    {
        return $this->render('brand');
    }

    public function actionTag($id)
    {
        return $this->render('tag');
    }

    public function actionProduct($id)
    {
        return $this->render('product');
    }
}
<?php

namespace frontend\controllers\shop;

class CatalogController extends \yii\web\Controller
{
    public $layout = 'catalog';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
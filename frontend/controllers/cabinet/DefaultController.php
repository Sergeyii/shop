<?php

namespace frontend\controllers\cabinet;

use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function behaviors(){
         return [
             'access' => [
                 'class' => AccessControl::className(),
                 'rules' => [
                     // allow authenticated users
                    [
                         'allow' => true,
                        'roles' => ['@'],
                    ],
                 ],
            ],
         ];
    }

    public function actionIndex(){
        return $this->render('index');
    }
}
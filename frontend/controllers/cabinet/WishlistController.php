<?php

namespace frontend\controllers\cabinet;

use shop\services\cabinet\WishlistService;
use shop\readModels\Shop\ProductReadRepository;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;

class WishlistController extends Controller
{
    public $service;
    public $products;

    public function __construct(string $id, $module, WishlistService $service, ProductReadRepository $products, array $config = [])
    {
        $this->service = $service;
        $this->products = $products;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
               'class' => VerbFilter::class,
               'actions' => [
                   'add' => ['POST'],
                   'delete' => ['POST'],
               ],
           ],
       ];
    }

    public function actionIndex(){
        $dataProvider = $this->products->getWishList(Yii::$app->user->id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdd($id){
        $resultStatus = [];

        try{
            $this->service->add(Yii::$app->user->id, $id);

            $resultStatus = ['success' => 'Success!'];
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);

            $resultStatus = ['error' => $e->getMessage()];
        }

        return $this->asJson($resultStatus);
    }

    public function actionDelete($id){
        try{
            $this->service->remove(Yii::$app->user->id, $id);
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

}
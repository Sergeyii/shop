<?php

namespace backend\controllers\shop;

use forms\manage\Shop\Product\ProductImportForm;
use shop\entities\Shop\Product\Modification;
use shop\forms\manage\Shop\Product\PhotosForm;
use shop\forms\manage\Shop\Product\ProductCreateForm;
use shop\forms\manage\Shop\Product\ProductEditForm;
use shop\readModels\Shop\BrandReadRepository;
use shop\services\manage\Shop\ProductManageService;
use Yii;
use shop\entities\Shop\Product\Product;
use backend\forms\Shop\ProductSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    private $service;

    public function __construct(string $id, $module, ProductManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'activate' => ['POST'],
                    'draft' => ['POST'],
                    'delete-photo' => ['POST'],
                    'move-photo-up' => ['POST'],
                    'move-photo-down' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    public function actionView($id)
    {
        $product = $this->findModel($id);
        $modificationsProvider = new ActiveDataProvider([
            'query' => $product->getModifications()->orderBy('name'),
            'key' => function (Modification $modification) use ($product) {
                return [
                    'product_id' => $product->id,
                    'id' => $modification->id,
                ];
            },
            'pagination' => false,
        ]);

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($product->id, $photosForm);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('view', [
            'product' => $product,
            'modificationsProvider' => $modificationsProvider,
            'photosForm' => $photosForm,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new ProductCreateForm();
        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $product = $this->service->create($form);

                return $this->redirect(['view', 'id' => $product->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $product = $this->findModel($id);

        $brandReadRepository = new BrandReadRepository();
        $form = new ProductEditForm($product, $brandReadRepository);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($product->id, $form);
                return $this->redirect(['view', 'id' => $product->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'product' => $product,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try{
            $this->service->remove($id);
        }catch(\Exception $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionActivate($id)
    {
        try{
            $this->service->activate($id);
        }catch(\DomainException $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionDraft($id)
    {
        try{
            $this->service->draft($id);
        }catch(\DomainException $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionDeletePhoto($id, $photo_id)
    {
        try{
            $this->service->removePhoto($id, $photo_id);
        }catch(\DomainException $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionMovePhotoDown($id, $photo_id)
    {
        try{
            $this->service->movePhotoDown($id, $photo_id);
        }catch(\DomainException $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionMovePhotoUp($id, $photo_id)
    {
        try{
            $this->service->movePhotoUp($id, $photo_id);
        }catch(\DomainException $e){
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionImport()
    {
        $form = new ProductImportForm();
        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->import($form);

                return $this->redirect(['index']);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\controllers\shop;

use shop\entities\Shop\Order\Order;
use shop\forms\manage\Shop\Order\OrderEditForm;
use shop\services\manage\Shop\OrderManageService;
use Yii;
use backend\forms\Shop\OrderSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class OrderController extends Controller
{
    private $service;

    public function __construct(string $id, $module, OrderManageService $service, array $config = [])
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
                    'export' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionExport()
    {
        try{
            //Просто взять все заказы и вывести их в документ Excel
            $orders = Order::find()->orderBy(['id' => SORT_DESC]);

            $objPHPExcel = new \PHPExcel();

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Ivan Dragomirov")
                ->setLastModifiedBy("Ivan Dragomirov")
                ->setTitle("Orders Document")
                ->setSubject("Orders Document")
                ->setDescription("Orders Document, generated using PHP classes.")
                ->setKeywords("Orders Document")
                ->setCategory("Orders Document");


            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Order ID')
                ->setCellValue('B1', 'Order created date');

            $worksheet = $objPHPExcel->getActiveSheet();

            foreach($orders->each() as $row => $order){
                /* @var Order $order */
                $worksheet->setCellValueByColumnAndRow(0, $row+1, $order->id);
                $worksheet->setCellValueByColumnAndRow(1, $row+1, date('Y-m-d H:i:s', $order->created_at));
            }

            //Вернуть документ в виде файла на выход
            $file = tempnam(sys_get_temp_dir(), 'export');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($file);

            return Yii::$app->response->sendFile($file, 'Orders.xlsx');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'order' => $this->service->get($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $order = $this->service->get($id);
        $form = new OrderEditForm($order);

        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($order->id, $form);
                return $this->redirect(['view', 'id' => $order->id]);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'order' => $order,
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
        return $this->redirect(['index']);
    }
}

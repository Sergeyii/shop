<?php

namespace frontend\controllers\cabinet;

use shop\entities\User\User;
use shop\forms\User\ProfileEditForm;
use shop\useCases\cabinet\ProfileService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class ProfileController extends Controller
{
    private $service;

    public function __construct(string $id, $module, ProfileService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function actionEdit()
    {
        $user = $this->findModel(Yii::$app->user->id);

        $form = new ProfileEditForm($user);
        if( $form->load(Yii::$app->request->post()) && $form->validate() ){
            try{
                $this->service->edit($user->id, $form);
                return $this->redirect(['cabinet/default/index']);
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('edit', [
            'model' => $form,
            'user' => $user,
        ]);
    }

    private function findModel($id)
    {
        if( $model = User::findOne($id) ){
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page doesn\'t exists.');
        }
    }
}
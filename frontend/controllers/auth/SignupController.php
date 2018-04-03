<?php

namespace frontend\controllers\auth;

use \Yii;
use yii\web\Controller;
use shop\useCases\auth\SignupService;
use shop\forms\auth\SignupForm;
use yii\filters\AccessControl;

class SignupController extends Controller
{
    private $signupService;

    public function __construct($id, $module, array $config = [], SignupService $signupService)
    {
        parent::__construct($id, $module, $config);

        $this->signupService = $signupService;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout', 'signup'],
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    /*[
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],*/
                ],
            ],
        ];
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate() ) {

            try {
                $this->signupService->signup($form);
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('signup', [
            'model' => $form,
        ]);
    }

    public function actionConfirm($token)
    {
        try{
            $this->signupService->confirm($token);
            Yii::$app->session->setFlash('success', 'Your email is confirmed');

            return $this->redirect(['auth/auth/login']);
        }catch(\DomainException $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->goHome();
        }
    }
}
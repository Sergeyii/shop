<?php

namespace frontend\controllers\auth;

use common\auth\Identity;
use shop\forms\auth\LoginForm;
use Yii;
use shop\services\auth\AuthService;
use yii\web\Controller;

class AuthController extends Controller
{
    private $authService;

    public function __construct($id, $module, array $config = [], AuthService $authService)
    {
        parent::__construct($id, $module, $config);

        $this->authService = $authService;
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $user = $this->authService->auth($form);
                Yii::$app->user->login(new Identity($user), $form->rememberMe ? 3600*24*30 : 0);
                return $this->goBack();
            }catch(\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
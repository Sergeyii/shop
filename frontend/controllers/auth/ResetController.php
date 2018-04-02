<?php

namespace frontend\controllers\auth;

use Yii;
use yii\web\Controller;
use shop\useCases\auth\PasswordResetService;
use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

class ResetController extends Controller
{
    private $passwordResetService;

    public function __construct($id, $module, array $config = [], PasswordResetService $passwordResetService)
    {
        parent::__construct($id, $module, $config);

        $this->passwordResetService = $passwordResetService;
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequest()
    {
        $form = new PasswordResetRequestForm();
        if ( $form->load(Yii::$app->request->post()) && $form->validate() ) {

            try{
                $this->passwordResetService->request($form);

                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }catch(\Exception $e){
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('/auth/request', [
            'model' => $form,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionConfirm($token)
    {
        try{
            $this->passwordResetService->validateToken($token);
        }catch(\DomainException $e){
            throw new BadRequestHttpException($e->getMessage());
        }

        $form = new ResetPasswordForm($token);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $this->passwordResetService->reset($token, $form);
                Yii::$app->session->setFlash('success', 'New password saved.');

                return $this->goHome();
            }catch(InvalidParamException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('/auth/confirm', [
            'model' => $form,
        ]);
    }
}
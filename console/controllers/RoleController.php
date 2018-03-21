<?php

namespace console\controllers;

use shop\entities\User\User;
use shop\services\manage\UserManageService;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use \Yii;

class RoleController extends Controller
{
    private $service;

    public function __construct(string $id, $module, UserManageService $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function actionAssign(): void
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $role = $this->select('Role:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        $this->service->assignRole($user->id, $role);
        $this->stdout('Done!'.PHP_EOL);
    }

    private function findModel($username): User
    {
        if( !$model = User::findOne(['username' => $username]) ){
            throw new Exception('User not found.');
        }
        return $model;
    }
}
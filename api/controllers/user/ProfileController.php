<?php

namespace api\controllers\user;

use api\formatters\UserFormatter;
use shop\entities\User\User;
use Yii;
use yii\rest\Controller;

class ProfileController extends Controller
{
    protected function verbs(): array
    {
        return [
            'index' => ['get'],
        ];
    }

    public function actionIndex()
    {
        $model = $this->findModel();
        return (new UserFormatter($model))->format();
    }

    public function findModel(): ?User
    {
        $model = User::findOne(Yii::$app->user->id);
        return $model;
    }
}
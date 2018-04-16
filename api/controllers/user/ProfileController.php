<?php

namespace api\controllers\user;

use api\formatters\UserProfileFormatter;
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

    /**
     * @SWG\Get(
     *     path="/profile",
     *     tags={"Profile"},
     *     description="Returns profile info",
     *     @SWG\Response(
     *          response=200,
     *          description="Success response",
     *          @SWG\Schema(ref="#/definitions/Profile")
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function actionIndex()
    {
        $model = $this->findModel();
        return (new UserProfileFormatter($model))->format();
    }

    public function findModel(): ?User
    {
        $model = User::findOne(Yii::$app->user->id);
        return $model;
    }
}

/**
 * @SWG\Definition(
 *     definition="Profile",
 *     type="object",
 *     required={"id"},
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="email", type="string"),
 *     @SWG\Property(property="city", type="string"),
 *     @SWG\Property(property="role", type="string")
 * )
 * */
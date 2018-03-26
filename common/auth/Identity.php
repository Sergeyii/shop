<?php

namespace common\auth;

use OAuth2\Storage\UserCredentialsInterface;
use shop\entities\User\User;
use shop\readModels\UserReadRepository;
use yii\base\Module;
use yii\web\IdentityInterface;
use Yii;

class Identity implements IdentityInterface, UserCredentialsInterface
{
    private $user;

    private static $repository=null;//Удалить когда решу проблему с bootstrap

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function findIdentity($id): ?self
    {
        $user = self::getRepository()->findActiveById($id);
        return $user ? new self($user) : null;
    }

    public function checkUserCredentials($username, $password)
    {
        if( !$user = $this->getRepository()->findActiveByUsername($username) ){
            return false;
        }
        return $user->validatePassword($password);
    }

    public function getUserDetails($username)
    {
        if( !$user = $this->getRepository()->findActiveByUsername($username) ){
            return false;
        }
        return ['user_id' => $user->id];
    }

    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        $token = static::getOauth()->getServer()->getResourceController()->getToken();
        return !empty($token['user_id']) ? static::findIdentity($token['user_id']) : null;
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getAuthKey(): string
    {
        return $this->user->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    private static function getRepository(): UserReadRepository
    {
        if( self::$repository === null){
            self::$repository = new UserReadRepository();
        }
        return self::$repository;
        //return \Yii::$app->get(UserReadRepository::class);
    }

    public static function getOauth(): Module
    {
        return Yii::$app->getModule('oauth2');
    }
}
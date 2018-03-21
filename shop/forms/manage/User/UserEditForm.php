<?php

namespace shop\forms\manage\User;

use shop\entities\User\User;
use shop\helpers\UserHelper;
use yii\base\Model;

class UserEditForm extends Model
{
    public $username;
    public $email;
    public $role;

    private $_user;

    public function __construct(User $user, array $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $roles = \Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = $roles ? reset($roles)->name : null;
        $this->_user = $user;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'role'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class, 'filter' => $this->_user ? ['<>', 'id', $this->_user->id] : null],
        ];
    }

    public function rolesList(): array
    {
        return UserHelper::rolesList();
    }
}
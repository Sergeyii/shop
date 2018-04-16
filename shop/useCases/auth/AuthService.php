<?php

namespace shop\useCases\auth;

use shop\entities\User\User;
use shop\forms\auth\LoginForm;
use shop\repositories\UserRepository;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users){
        $this->users = $users;
    }

    public function auth(LoginForm $form):User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);
        if( !$user || !$user->isActive() || !$user->validatePassword($form->password) ){
            throw new \DomainException('Undefined user or password.');
        }

        return $user;
    }




    //------------------

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    /*public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }*/

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    /*protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }*/
    //------------------
}
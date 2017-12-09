<?php

namespace shop\services\auth;

use shop\entities\User\User;
use shop\repositories\UserRepository;

class NetworkService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth($network, $identity):User
    {
        //Нашли пользователя с такой соц. сетью => возвращаем
        if( $user = $this->users->findByNetworkIdentity($network, $identity) ){
            return $user;
        }

        //Иначе привязываем соц. сеть и возвращаем
        $user = User::signupByNetwork($network, $identity);
        $user->save();

        return $user;
    }

    public function attach($id, $network, $identity):void
    {
        //Нашли пользователя с такой соц. сетью => возвращаем
        if( $this->users->findByNetworkIdentity($network, $identity) ){
            throw new \DomainException('Network is already attached!');
        }

        //Получаем пользователя
        $user = $this->users->get($id);
        //Привязываем к нему соц. сеть
        $user->attachNetwork($network, $identity);
        //Сохраняем связь
        $this->users->save($user);
    }
}
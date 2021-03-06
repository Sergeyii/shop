<?php

namespace shop\repositories;

use shop\dispatchers\EventDispatcher;
use shop\entities\User\User;
use shop\repositories\NotFoundException;

class UserRepository
{
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function findByUsernameOrEmail(string $value):?User
    {
        return $this->getBy(['or', ['username' => $value], ['email' => $value]]);
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    public function getByEmailConfirmToken(string $token)
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail(string $email):User
    {
        return $this->getBy(['email' => $email]);
    }

    public function getByPasswordResetToken(string $token):User
    {
        return $this->getBy(['password_reset_token' => $token,'status' => User::STATUS_ACTIVE]);
    }

    public function existsByPasswordResetToken(string $token):bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function getAllByProductInWishList($productId): iterable
    {
        return User::find()
            ->alias('u')
            ->joinWith('wishlistItems w', false, 'INNER JOIN')
            ->andWhere(['w.product_id' => $productId])
            ->each();
    }

    public function save(User $user):void
    {
        if (!$user->save()) {
            throw new \RuntimeException('User saving error.');
        }
        $this->dispatcher->dispatchAll($user->releaseEvents());
    }

    public function remove(User $user):void
    {
        if (!$user->delete()) {
            throw new \RuntimeException('User removing error.');
        }
        $this->dispatcher->dispatchAll($user->releaseEvents());
    }

    private function getBy(array $criteria):User
    {
        if( !($user = User::find()->andWhere($criteria)->limit(1)->one()) ){
            throw new NotFoundException('User is not found.');
        }

        return $user;
    }

    public function get(int $id):User
    {
        return $this->getBy(['id' => $id]);
    }
}
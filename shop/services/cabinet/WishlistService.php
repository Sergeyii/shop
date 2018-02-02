<?php

namespace shop\services\cabinet;

use shop\repositories\Shop\ProductRepository;
use shop\repositories\UserRepository;

class WishlistService
{
    public $users;
    public $products;

    public function __construct(UserRepository $users, ProductRepository $products)
    {
        $this->users = $users;
        $this->products = $products;
    }

    public function add($userId, $productId): void
    {
        $user = $this->users->get($userId);
        $product = $this->products->get($productId);

        $user->addToWishlist($product->id);
        $this->users->save($user);
    }

    public function remove($userId, $productId): void
    {
        $user = $this->users->get($userId);
        $product = $this->products->get($productId);

        $user->removeFromWishlist($product->id);
        $this->users->save($user);
    }
}
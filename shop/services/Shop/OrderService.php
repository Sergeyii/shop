<?php

namespace shop\services\Shop;

use shop\cart\Cart;
use shop\cart\CartItem;
use shop\entities\Shop\Order\CustomerData;
use shop\entities\Shop\Order\DeliveryData;
use shop\entities\Shop\Order\Order;
use shop\entities\Shop\Order\OrderItem;
use shop\entities\Shop\Product\Product;
use shop\forms\Shop\Order\OrderForm;
use shop\repositories\Shop\DeliveryMethodRepository;
use shop\repositories\Shop\OrderRepository;
use shop\repositories\Shop\ProductRepository;
use shop\repositories\UserRepository;
use shop\services\TransactionManager;

class OrderService
{
    private $users;
    private $cart;
    private $deliveryMethods;
    private $transaction;
    private $orders;
    private $products;

    public function __construct(
        Cart $cart,
        OrderRepository $orders,
        ProductRepository $products,
        UserRepository $users,
        DeliveryMethodRepository $deliveryMethods,
        TransactionManager $transaction
    )
    {
        $this->users = $users;
        $this->cart = $cart;
        $this->deliveryMethods = $deliveryMethods;
        $this->transaction = $transaction;
        $this->orders = $orders;
        $this->products = $products;
    }

    public function checkout($userId, OrderForm $form): Order
    {
        $user = $this->users->get($userId);
        /* @var Product[] $products */
        $products = [];

        $items = array_map(function(CartItem $item) use (&$products){
            $product = $item->getProduct();
            $product->checkout($item->getModificationId(), $item->getQuantity());
            $products[] = $product;

            return OrderItem::create(
                $product,
                $item->getModificationId(),
                $item->getPrice(),
                $item->getQuantity()
            );
        }, $this->cart->getItems());

        $order = Order::create(
            $user->id,
            new CustomerData(
                $form->customer->phone,
                $form->customer->name
            ),
            $items,
            $this->cart->getCost()->getTotal(),
            $form->note
        );

        $order->setDeliveryInfo(
            $this->deliveryMethods->get($form->delivery->method),
            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $this->transaction->wrap(function() use($order, $products){
            $this->orders->save($order);
            $this->products->saveAll($products);

            $this->cart->clear();
        });

        return $order;
    }
}
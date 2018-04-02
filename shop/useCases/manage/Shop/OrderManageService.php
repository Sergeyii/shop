<?php

namespace shop\useCases\manage\Shop;

use shop\entities\Shop\DeliveryMethod;
use shop\entities\Shop\Order\CustomerData;
use shop\entities\Shop\Order\DeliveryData;
use shop\entities\Shop\Order\Order;
use shop\forms\manage\Shop\Order\OrderEditForm;
use shop\repositories\Shop\DeliveryMethodRepository;
use shop\repositories\Shop\OrderRepository;

class OrderManageService
{
    private $orders;
    private $deliveryMethods;

    public function __construct(OrderRepository $repository, DeliveryMethodRepository $deliveryMethods)
    {
        $this->orders = $repository;
        $this->deliveryMethods = $deliveryMethods;
    }

    public function get($id): Order
    {
        return $this->orders->get($id);
    }

    public function edit($id, OrderEditForm $form): void
    {
        $order = $this->orders->get($id);
        $order->edit(
            new CustomerData(
                $form->customer->phone,
                $form->customer->name
            ), $form->note
        );

        $deliveryMethod = $this->deliveryMethods->get($form->delivery->method);
        $order->setDeliveryInfo(
            $deliveryMethod,
            new DeliveryData(
                $form->delivery->index,
                $form->delivery->address
            )
        );

        $this->orders->save($order);
    }

    public function remove($id): void
    {
        $order = $this->get($id);
        $this->orders->remove($order);
    }
}
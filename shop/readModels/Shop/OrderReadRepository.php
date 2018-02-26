<?php

namespace shop\readModels\Shop;

use shop\entities\Shop\Order\Order;
use shop\repositories\NotFoundException;
use yii\data\ActiveDataProvider;

class OrderReadRepository
{
    public function getOwn($userId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Order::find()
                ->andWhere(['user_id' => $userId])
                ->orderBy(['id' => SORT_DESC]),
            'sort' => false,
        ]);
    }

    public function findOwn($userId, $id): ?Order
    {
        if( !($order = Order::find()->andWhere(['user_id' => $userId, 'id' => $id])->one()) ){
            throw new NotFoundException('Order not found.');
        }

        return $order;
    }
}
<?php

namespace shop\cart\storage;

use shop\cart\CartItem;
use shop\entities\Shop\DbCartItem;
use yii\db\Connection;

class DbStorage implements StorageInterface
{
    private $userId;
    private $db;

    public function __construct($userId, Connection $db)
    {
        $this->userId = $userId;
        $this->db = $db;
    }

    public function load(): array
    {
        $dbCartItems = $this->getItems();
        return array_map(function(DbCartItem $dbCartItem){
            $product = $dbCartItem->product;
            return new CartItem($product, $dbCartItem->modification_id, $dbCartItem->quantity);
        }, $dbCartItems);
    }

    public function save(array $items): void
    {
        $this->db->createCommand()->delete(DbCartItem::tableName(), [
            'user_id' => $this->userId,
        ])->execute();

        $this->db->createCommand()->batchInsert(
            DbCartItem::tableName(),
            [
                'user_id',
                'product_id',
                'modification_id',
                'quantity'
            ],
            array_map(function (CartItem $item) {
                return [
                    'user_id' => $this->userId,
                    'product_id' => $item->getProductId(),
                    'modification_id' => $item->getModificationId(),
                    'quantity' => $item->getQuantity(),
                ];
            }, $items)
        )->execute();
    }

    private function getItems(): array
    {
        $dbCartItems = DbCartItem::find()->andWhere(['user_id' => $this->userId])->all();
        return $dbCartItems;
    }
}
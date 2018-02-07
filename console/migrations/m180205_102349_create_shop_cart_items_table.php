<?php

use yii\db\Migration;

class m180205_102349_create_shop_cart_items_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('shop_cart_items', [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'modification_id' => $this->integer()->notNull(),
            'user_hash' => $this->string(32)->notNull(),
            'quantity' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-shop_cart_items-user_id', 'shop_cart_items', ['user_id']);
        $this->createIndex('idx-shop_cart_items-product_id', 'shop_cart_items', ['product_id']);
        $this->createIndex('idx-shop_cart_items-modification_id', 'shop_cart_items', ['modification_id']);

        $this->addForeignKey('fk-shop_cart_items-user_id', 'shop_cart_items', ['user_id'], 'users', 'id', 'CASCADE');
        $this->addForeignKey('fk-shop_cart_items-product_id', 'shop_cart_items', ['product_id'], 'shop_products', 'id', 'CASCADE');
        $this->addForeignKey('fk-shop_cart_items-modification_id', 'shop_cart_items', ['modification_id'], 'shop_modifications', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('shop_cart_items');
    }
}

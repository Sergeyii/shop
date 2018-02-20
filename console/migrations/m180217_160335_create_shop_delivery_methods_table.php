<?php

use yii\db\Migration;

class m180217_160335_create_shop_delivery_methods_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('shop_delivery_methods', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'cost' => $this->decimal(7,2)->notNull(),
            'min_weight' => $this->integer(),
            'max_weight' => $this->integer(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('shop_delivery_methods');
    }
}

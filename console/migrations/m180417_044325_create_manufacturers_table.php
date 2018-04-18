<?php

use yii\db\Migration;

class m180417_044325_create_manufacturers_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('manufacturers', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'description' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx-manufacturers-slug', 'manufacturers', 'slug', true);
    }

    public function down()
    {
        $this->dropTable('manufacturers');
        $this->dropIndex('idx-manufacturers-slug', 'manufacturers');
    }
}
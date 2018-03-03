<?php

use yii\db\Migration;

class m180302_135445_create_blog_categories_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('blog_categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'sort' => $this->integer()->notNull(),
            'meta_json' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx-blog_categories-slug', 'blog_categories', 'slug', true);
    }

    public function down()
    {
        $this->dropTable('blog_categories');
    }
}

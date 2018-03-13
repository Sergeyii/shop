<?php

use yii\db\Migration;

class m180308_093844_add_blog_comments_count_field extends Migration
{
    public function up()
    {
        $this->addColumn('blog_posts', 'comments_count', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('blog_posts', 'comments_count');
    }
}
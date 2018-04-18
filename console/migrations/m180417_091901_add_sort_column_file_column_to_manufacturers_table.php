<?php

use yii\db\Migration;

class m180417_091901_add_sort_column_file_column_to_manufacturers_table extends Migration
{
    public function up()
    {
        $this->addColumn('manufacturers', 'sort', $this->integer()->notNull());
        $this->addColumn('manufacturers', 'file', $this->string()->notNull());
    }

    public function down()
    {
        $this->dropColumn('manufacturers', 'sort');
        $this->dropColumn('manufacturers', 'file');
    }
}

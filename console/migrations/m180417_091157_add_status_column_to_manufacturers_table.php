<?php

use yii\db\Migration;

class m180417_091157_add_status_column_to_manufacturers_table extends Migration
{
    public function up()
    {
        $this->addColumn('manufacturers', 'status', $this->boolean()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('manufacturers', 'status');
    }
}
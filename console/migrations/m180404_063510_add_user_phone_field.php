<?php

use yii\db\Migration;

class m180404_063510_add_user_phone_field extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'phone', $this->string());
        $this->createIndex('idx-users-phone', 'users', 'phone', true);
    }

    public function down()
    {
        $this->dropColumn('users', 'phone');
        $this->dropIndex('idx-users-phone', 'users');
    }
}
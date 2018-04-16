<?php

use yii\db\Migration;

class m180320_065427_change_auth_assignments_table extends Migration
{
    public function up()
    {
        $this->alterColumn('auth_assignments', 'user_id', $this->integer()->notNull());
        $this->createIndex('idx-auth_assignments-user_id', 'auth_assignments', 'user_id');
        $this->addForeignKey('fk-auth_assignments-user_id', 'auth_assignments', 'user_id', 'users', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk-auth_assignments-user_id', 'auth_assignments');
        $this->dropIndex('idx-auth_assignments-user_id', 'auth_assignments');
        $this->alterColumn('auth_assignments', 'user_id', $this->string(64)->notNull());
    }
}
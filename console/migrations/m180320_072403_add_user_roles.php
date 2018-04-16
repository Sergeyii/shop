<?php

use yii\db\Migration;

class m180320_072403_add_user_roles extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('auth_items', ['type', 'name', 'description'], [
            [1, 'user', 'User'],
            [2, 'admin', 'Admin'],
        ]);

        $this->batchInsert('auth_item_children', ['parent', 'child'], [
            ['user', 'admin'],
        ]);

        $this->execute('INSERT INTO {{%auth_assignments}} (item_name, user_id) SELECT \'user\', u.id FROM {{%users}} u ORDER BY u.id');
    }

    public function safeDown()
    {
        $this->delete('auth_items', ['name' => ['user', 'admin']]);
    }
}

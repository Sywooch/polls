<?php
use yii\db\Schema;
use yii\db\Migration;

class m150630_133849_create_poll_table extends Migration
{
    public function up()
    {
        $this->createTable('poll', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'auth_key' => Schema::TYPE_STRING . '(32) UNIQUE',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'is_results_visible' => Schema::TYPE_INTEGER . ' NOT NULL',
            'people_count' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP NOT NULL',
            'FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE'
        ], 'Engine=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('poll');
    }
}

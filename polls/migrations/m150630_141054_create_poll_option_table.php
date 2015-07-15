<?php
use yii\db\Schema;
use yii\db\Migration;

class m150630_141054_create_poll_option_table extends Migration
{
    public function up()
    {
        $this->createTable('poll_option', [
            'id' => Schema::TYPE_PK,
            'poll_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . '(60) NOT NULL',
            'position' => Schema::TYPE_INTEGER . ' NOT NULL',
            'people_count' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'FOREIGN KEY (poll_id) REFERENCES poll(id) ON DELETE CASCADE'
        ], 'Engine=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('poll_option');
    }
}

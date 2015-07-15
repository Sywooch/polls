<?php

use yii\db\Schema;
use yii\db\Migration;

class m150702_155331_create_poll_ip_table extends Migration
{
    public function up()
    {
        $this->createTable('poll_ip', [
            'poll_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_ip' => Schema::TYPE_STRING . '(60) NOT NULL',
            'PRIMARY KEY (poll_id, user_ip)',
            'FOREIGN KEY (poll_id) REFERENCES poll(id) ON DELETE CASCADE'
        ], 'Engine=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }

    public function down()
    {
        $this->dropTable('poll_ip');
    }
}

<?php
namespace app\models;

use yii\db\ActiveRecord;

class PollIp extends ActiveRecord
{
    public static function tableName()
    {
        return 'poll_ip';
    }
}

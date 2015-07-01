<?php
namespace app\models;

use yii\db\ActiveRecord;

class PollOption extends ActiveRecord
{
    public static function tableName()
    {
        return 'poll_option';
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 60]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Текст варианта'
        ];
    }

    public function getPoll()
    {
        return $this->hasOne(Poll::className(), ['id' => 'poll_id']);
    }
}

<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Poll extends ActiveRecord
{
    const TYPE_CHECKBOX = 0;
    const TYPE_RADIO = 1;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Текст опроса',
            'type' => 'Тип опроса'
        ];
    }

    public function rules()
    {
        return [
            [['title', 'type'], 'required'],
            ['title', 'string', 'max' => 255],
            ['type', 'in', 'range' => [static::TYPE_CHECKBOX, static::TYPE_RADIO]]
        ];
    }

    public function getPollOptions()
    {
        return $this->hasMany(PollOption::className(), ['poll_id' => 'id'])->orderBy('position ASC');
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Poll extends ActiveRecord
{
    const TYPE_CHECKBOX = 0;
    const TYPE_RADIO = 1;

//    public function behaviors()
//    {
//        return [
//            [
//                'class' => TimestampBehavior::className(),
//                'createdAtAttribute' => 'created_at',
//                'updatedAtAttribute' => false,
//                'value' => new Expression('NOW()'),
//            ]
//        ];
//    }

    public function attributeLabels()
    {
        return [
            'title' => 'Текст опроса',
            'type' => 'Тип опроса',
            'is_results_visible' => 'Видимость результатов',
            'people_count' => 'Количество проголосовавших',
            'created_at' => 'Дата создания'
        ];
    }

    public function rules()
    {
        return [
            [['title', 'type', 'is_results_visible'], 'required'],
            ['title', 'trim'],
            ['title', 'string', 'max' => 25],
            ['type', 'in', 'range' => [static::TYPE_CHECKBOX, static::TYPE_RADIO]],
            ['is_results_visible', 'boolean']
        ];
    }

    public function toggleVisibility()
    {
        $this->is_results_visible = $this->is_results_visible ? 0 : 1;

        return $this;
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

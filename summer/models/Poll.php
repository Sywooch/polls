<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\Cookie;

class Poll extends ActiveRecord
{
    const TYPE_CHECKBOX = 0;
    const TYPE_RADIO = 1;

    const MAX_POLLS_KEYS_IN_COOKIES = 10;
    const POLLS_KEYS_EXPIRE = 31536000; //365 days

    protected $userAuthKeys;

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

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getAuthKeys()
    {
        if (!isset($this->userAuthKeys)) {
            $pollsKeys = Yii::$app->request->cookies->getValue('polls_keys', []);
            if ($pollsKeys !== []) {
                $pollsKeys = explode(',', $pollsKeys);
            }

            $this->userAuthKeys = $pollsKeys;
        }

        return $this->userAuthKeys;
    }

    public function saveAuthKeyInCookies()
    {
        $pollsKeys = $this->getAuthKeys();
        $pollsKeys = array_slice($pollsKeys, -(static::MAX_POLLS_KEYS_IN_COOKIES - 1));
        $pollsKeys[] = $this->auth_key;

        $this->userAuthKeys = $pollsKeys;

        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'polls_keys',
            'value' => implode(',', $pollsKeys),
            'expire' => time() + static::POLLS_KEYS_EXPIRE
        ]));
    }

    public function removeAuthKeyFromCookies()
    {
        $pollsKeys = $this->getAuthKeys();

        if (($pollKey = array_search($this->auth_key, $pollsKeys)) !== false) {
            $pollsKeys = array_splice($pollsKeys, $pollKey, 1);

            $this->userAuthKeys = $pollsKeys;

            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'polls_keys',
                'value' => implode(',', $pollsKeys),
                'expire' => time() + static::POLLS_KEYS_EXPIRE
            ]));
        }
    }

    public function clearAuthKeys()
    {
        $this->userAuthKeys = [];

        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'polls_keys',
            'value' => '',
            'expire' => time() - 1
        ]));
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

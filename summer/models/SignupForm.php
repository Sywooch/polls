<?php
namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Данный email уже используется.'],

            ['password', 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();

            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();

            if ($user->save()) {
                Yii::$app->authManager->assign(Yii::$app->authManager->getRole('user'), $user->id);
                Yii::$app->getUser()->login($user);

                return $user;
            }
        }

        return null;
    }
}

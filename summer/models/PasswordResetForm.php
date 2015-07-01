<?php
namespace app\models;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;

class PasswordResetForm extends Model
{
    public $password;

    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if ($token === '' || !is_string($token)) {
            throw new InvalidParamException('Токен сброса пароля не может быть пустым.');
        }

        $this->_user = User::findByPasswordResetToken($token);

        if (!$this->_user) {
            throw new InvalidParamException('Неправильный токен сброса пароля.');
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль'
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        if ($user->save(false)) {
            Yii::$app->getUser()->login($user);

            return true;
        } else {
            return false;
        }
    }
}

<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $captcha;

    public function init()
    {
        parent::init();

        if (!Yii::$app->user->isGuest) {
            $this->email = Yii::$app->user->identity->email;
        }
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'subject', 'body', 'captcha'], 'required'],
            ['email', 'email'],
            ['captcha', 'captcha']
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'subject' => 'Тема сообщения',
            'body' => 'Сообщение',
            'captcha' => 'Проверочный код'
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom($this->email)
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        } else {
            return false;
        }
    }
}

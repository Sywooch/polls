<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\SignupForm;
use app\models\LoginForm;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use app\models\RequestPasswordResetForm;
use app\models\PasswordResetForm;
use app\models\ContactForm;
use app\models\Poll;
use app\models\PollOption;
use app\models\User;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['signup', 'password-reset', 'request-password-reset', 'login', 'logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['signup', 'password-reset', 'request-password-reset', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $poll = new Poll();
            $pollOptions = [new PollOption()];

            $signupForm = new SignupForm();

            $usersCount = User::find()->count();
            $pollsCount = Poll::find()->count();


            return $this->render('indexGuest', [
                'poll' => $poll,
                'pollOptions' => $pollOptions,
                'signupForm' => $signupForm,
                'usersCount' => $usersCount,
                'pollsCount' => $pollsCount
            ]);
        } else {
            $polls = Poll::find()->where(['user_id' => Yii::$app->user->identity->id])->orderBy('created_at')->all();

            return $this->render('indexUser', [
                'polls' => $polls
            ]);
        }
    }

    public function actionSignup()
    {
        $signupForm = new SignupForm();

        if ($signupForm->load(Yii::$app->request->post())) {
            if ($user = $signupForm->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', ['signupForm' => $signupForm]);
    }

    public function actionLogin()
    {
        $loginForm = new LoginForm();

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', ['loginForm' => $loginForm]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $requestPasswordResetForm = new RequestPasswordResetForm();

        if ($requestPasswordResetForm->load(Yii::$app->request->post()) && $requestPasswordResetForm->validate()) {
            if ($requestPasswordResetForm->sendEmail()) {
                Yii::$app->session->setFlash('success', 'На ваш email послана ссылка для сброса пароля.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Невозможно сбросить пароль для данного email.');
            }
        }

        return $this->render('requestPasswordReset', ['requestPasswordResetForm' => $requestPasswordResetForm]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionPasswordReset($token)
    {
        try {
            $passwordResetForm = new PasswordResetForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($passwordResetForm->load(Yii::$app->request->post()) && $passwordResetForm->validate() && $passwordResetForm->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль был сохранён');

            return $this->goHome();
        }

        return $this->render('passwordReset', ['passwordResetForm' => $passwordResetForm]);
    }

    public function actionContact()
    {
        $contactForm = new ContactForm();
        if ($contactForm->load(Yii::$app->request->post()) && $contactForm->contact(Yii::$app->params['supportEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted', 'Ваше сообщение отправлено.');

            return $this->refresh();
        } else {
            return $this->render('contact', ['contactForm' => $contactForm]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}

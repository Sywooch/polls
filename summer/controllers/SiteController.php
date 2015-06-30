<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\SignupForm;
use app\models\LoginForm;
use yii\web\BadRequestHttpException;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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
        return $this->render('index');
    }

    public function actionSignup()
    {
        $signupModel = new SignupForm();
        if ($signupModel->load(Yii::$app->request->post())) {
            if ($user = $signupModel->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', ['signupModel' => $signupModel]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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
        $passwordResetRequestForm = new PasswordResetRequestForm();
        if ($passwordResetRequestForm->load(Yii::$app->request->post()) && $passwordResetRequestForm->validate()) {
            if ($passwordResetRequestForm->sendEmail()) {
                Yii::$app->session->setFlash('success', 'На ваш email послана ссылка для сброса пароля.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Невозможно сбросить пароль для данного email.');
            }
        }

            return $this->render('requestPasswordReset', ['passwordResetRequestForm' => $passwordResetRequestForm]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $resetPasswordForm = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($resetPasswordForm->load(Yii::$app->request->post()) && $resetPasswordForm->validate() && $resetPasswordForm->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль был сохранён');

            return $this->goHome();
        }

        return $this->render('resetPassword', ['resetPasswordForm' => $resetPasswordForm]);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}

<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\ProfileForm;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['profile'],
                'rules' => [
                    [
                        'actions' => ['profile'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ]
            ]
        ];
    }

    public function actionProfile($id = null)
    {
        if ($id === null) {
            $id = Yii::$app->user->identity->id;
        }

        if (Yii::$app->user->can('viewProfile', ['id' => $id])) {
            $profileForm = new ProfileForm($id);

            if ($profileForm->load(Yii::$app->request->post()) && $profileForm->updateProfile()) {
                return $this->redirect(['site/index']);
            }

            return $this->render('profile', ['profileForm' => $profileForm, 'id' => $id]);
        } else {
            throw new ForbiddenHttpException('Доступ к изменению данного профиля запрещён');
        }
    }

    public function actionDelete($id)
    {
        if (Yii::$app->user->can('deleteUser')) {
            if (($user = User::findOne($id)) !== null) {
                $user->delete();

                return $this->redirect(['admin/users-control']);
            } else {
                throw new NotFoundHttpException('Пользователь не найден.');
            }
        } else {
            throw new ForbiddenHttpException('Доступ к удалению пользователей запрещён.');
        }
    }
}

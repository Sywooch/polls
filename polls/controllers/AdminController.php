<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use app\models\UserSearch;
use app\models\PollSearch;

class AdminController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->can('viewAdminPanel')) {
            return $this->render('index');
        } else {
            throw new ForbiddenHttpException('Доступ к панели администрирования запрещён.');
        }
    }

    public function actionUsersControl()
    {
        if (Yii::$app->user->can('useUsersControlPanel')) {
            $userSearch = new UserSearch();
            $usersDataProvider = $userSearch->search(Yii::$app->request->queryParams);

            return $this->render('usersControl', [
                'usersDataProvider' => $usersDataProvider,
                'userSearch' => $userSearch
            ]);
        } else {
            throw new ForbiddenHttpException('Доступ к панели управления пользователями запрещён.');
        }
    }

    public function actionPollsControl()
    {
        if (Yii::$app->user->can('usePollsControlPanel')) {
            $pollSearch = new PollSearch(['scenario' => 'admin-panel']);
            $pollsDataProvider = $pollSearch->search(Yii::$app->request->queryParams);

            return $this->render('pollsControl', [
                'pollsDataProvider' => $pollsDataProvider,
                'pollSearch' => $pollSearch
            ]);
        } else {
            throw new ForbiddenHttpException('Доступ к панели управления опросами запрещён.');
        }
    }
}

<?php
namespace app\controllers;

use Yii;
use app\models\Poll;
use app\models\PollOption;
use app\models\PollVote;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

class PollController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'toggle-visibility' => ['post']
                ]
            ]
        ];
    }

    public function actionCreate()
    {
        $poll = new Poll();

        $pollOptionsCount = count(Yii::$app->request->post('PollOption', []));
        $pollOptions = [new PollOption()];
        for($pollIndex = 1; $pollIndex < $pollOptionsCount; $pollIndex++) {
            $pollOptions[] = new PollOption();
        }

        if ($poll->load(Yii::$app->request->post()) && $poll->validate()) {
            if (Model::loadMultiple($pollOptions, Yii::$app->request->post()) && Model::validateMultiple($pollOptions)) {
                $poll->user_id = !Yii::$app->user->isGuest ?
                    Yii::$app->user->identity->getId() :
                    null;
                $poll->save(false);

                foreach ($pollOptions as $position => $pollOption) {
                    $pollOption->poll_id = $poll->id;
                    $pollOption->position = $position;

                    $pollOption->save(false);
                }

                return $this->redirect(['view', 'id' => $poll->id]);
            }
        }

        return $this->render('create', [
            'poll' => $poll,
            'pollOptions' => $pollOptions
        ]);
    }

    public function actionDelete($id)
    {
        $this->findPoll($id)->delete();

        return $this->redirect(['site/index']);
    }

    public function actionView($id)
    {
        $poll = $this->findPoll($id);
        $isValidUser = PollVote::getInstance($poll)->validateUser();
        $maxPeopleCount = max(ArrayHelper::getColumn($poll->pollOptions, 'people_count'));

        return $this->render('view', ['poll' => $poll, 'maxPeopleCount' => $maxPeopleCount, 'isValidUser' => $isValidUser]);
    }

    public function actionVote($id)
    {
        $pollVote = PollVote::getInstance($this->findPoll($id));

        if ($pollVote->validateUser()) {
            if ($pollVote->load(Yii::$app->request->post()) && $pollVote->vote()) {
                return $this->redirect(['view', 'id' => $pollVote->id]);
            }

            return $this->render('vote', ['pollVote' => $pollVote]);
        } else {
            return $this->redirect(['view', 'id' => $pollVote->id]);
        }
    }

    public function actionToggleVisibility($id)
    {
        $poll = $this->findPoll($id);
        $poll->toggleVisibility();
        $poll->save();

        return $this->redirect(['view', 'id' => $poll->id]);
    }

    protected function findPoll($id)
    {
        if (($poll = Poll::findOne($id)) !== null) {
            return $poll;
        } else {
            throw new NotFoundHttpException('Опрос не существует.');
        }
    }
}

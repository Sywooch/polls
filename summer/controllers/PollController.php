<?php
namespace app\controllers;

use Yii;
use app\models\Poll;
use app\models\PollOption;
use app\models\PollVote;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
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
        if (Yii::$app->user->can('createPoll')) {
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
        } else {
            throw new ForbiddenHttpException('Вы не можете просматривать эту страницу.');
        }
    }

    public function actionDelete($id)
    {
        $poll = $this->findPoll($id);

        if (Yii::$app->user->can('deletePoll', ['poll' => $poll])) {
            $poll->delete();

            return $this->redirect(['site/index']);
        } else {
            throw new ForbiddenHttpException('Вы не можете просматривать эту страницу.');
        }
    }

    public function actionView($id)
    {
        $poll = $this->findPoll($id);
        $pollVote = PollVote::getInstance($poll);
        $maxPeopleCount = max(ArrayHelper::getColumn($poll->pollOptions, 'people_count'));

        return $this->render('view', [
            'poll' => $poll,
            'pollVote' => $pollVote,
            'maxPeopleCount' => $maxPeopleCount,
            'canViewPollResults' => Yii::$app->user->can('viewPollResults', ['poll' => $poll])
        ]);
    }

    public function actionVote($id)
    {
        $pollVote = PollVote::getInstance($this->findPoll($id));

        if (Yii::$app->user->can('votePoll', ['pollVote' => $pollVote])) {
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

        if (Yii::$app->user->can('changePollVisibility', ['poll' => $poll])) {
            $poll->toggleVisibility()->save();

            return $this->redirect(['view', 'id' => $poll->id]);
        } else {
            throw new ForbiddenHttpException('Вы не можете просматривать эту страницу.');
        }
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

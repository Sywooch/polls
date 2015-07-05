<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use app\models\Poll;

$this->title = Yii::$app->name;
?>
<div class="row">
    <div class="col-md-8">
        <?= Alert::widget([
            'options' => ['class' => 'alert-info'],
            'body' => 'Без регистрации вы сможете управлять только последними <strong>' . Poll::MAX_POLLS_KEYS_IN_COOKIES . ' опросами</strong>, а также у вас будет отсутствовать доступ к панели управления опросами!<br><strong>После регистрации созданные вами опросы будут перенесены на аккаунт.</strong>',
        ]) ?>

        <h1 class="text-center">Создание нового опроса</h1>

        <?= $this->render('/poll/_form', ['poll' => $poll, 'pollOptions' => $pollOptions]) ?>
    </div>


    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1 class="panel-title text-center">Быстрая регистрация</h1>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'action' => ['site/signup']
                ]) ?>

                <?= $form->field($signupForm, 'email') ?>
                <?= $form->field($signupForm, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1 class="panel-title text-center">Статистика сервиса</h1>
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    <span class="badge"><?= $usersCount ?></span>
                    Всего пользователей:
                </li>
                <li class="list-group-item">
                    <span class="badge"><?= $pollsCount ?></span>
                    Всего опросов:
                </li>
            </ul>
        </div>
    </div>
</div>

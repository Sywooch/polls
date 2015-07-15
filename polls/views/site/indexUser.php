<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

$this->title = Yii::$app->name;
?>
<div class="row">
    <div class="col-md-8">
        <?= Html::a('Создать опрос', ['poll/create'], ['class' => 'btn btn-primary pull-right']) ?>
        <h1 class="text-center">Ваши опросы</h1>

        <?php if (count($polls) !== 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th>Название опроса</th>
                        <th>Количество проголосовавших</th>
                        <th>Дата создания</th>
                    </tr>

                    <?php foreach ($polls as $poll): ?>
                        <tr>
                            <td><?= Html::a(Html::encode($poll->title), ['poll/view', 'id' => $poll->id]) ?></td>
                            <td><?= Html::encode($poll->people_count) ?></td>
                            <td><?= Html::encode($poll->created_at) ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        <?php else: ?>
            <?= Alert::widget([
                'options' => ['class' => 'alert alert-info'],
                'closeButton' => false,
                'body' => 'Опросы отсутствуют.'
            ]) ?>
        <?php endif ?>

        <div class="text-center">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>


    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1 class="panel-title text-center">Поиск вашего опроса</h1>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['method' => 'GET', 'options' => ['data-pjax' => '0']]) ?>
                    <?= $form->field($pollSearch, 'title')->textInput(['placeholder' => $pollSearch->getAttributeLabel('title')])->label(false) ?>
                    <?= $form->field($pollSearch, 'people_count')->textInput(['placeholder' => $pollSearch->getAttributeLabel('people_count')])->label(false) ?>
                    <?= $form->field($pollSearch, 'pollOptionName')->textInput(['placeholder' => $pollSearch->getAttributeLabel('pollOptionName')])->label(false) ?>
                    <?= $form->field($pollSearch, 'created_at')->textInput(['placeholder' => $pollSearch->getAttributeLabel('created_at')])->label(false) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1 class="panel-title text-center">Сортировка</h1>
            </div>
            <div class="list-group">
                <?= $sort->link('title', ['class' => 'list-group-item']) ?>
                <?= $sort->link('people_count', ['class' => 'list-group-item']) ?>
                <?= $sort->link('created_at', ['class' => 'list-group-item']) ?>
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
                <li class="list-group-item">
                    <span class="badge"><?= $userPollsCount ?></span>
                    Ваших опросов:
                </li>
            </ul>
        </div>
    </div>
</div>

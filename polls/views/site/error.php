<?php
use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->title = 'Ошибка - ' . $exception->statusCode;
?>
<h1 class="text-center"><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <?= Alert::widget([
            'options' => ['class' => 'alert alert-danger'],
            'closeButton' => false,
            'body' => nl2br(Html::encode($message)) . '<br>' . Html::a('Вернуться на главную страницу.', ['site/index'], ['class' => 'alert-link'])
        ]) ?>
    </div>
</div>

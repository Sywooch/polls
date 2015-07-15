<?php
use yii\helpers\Html;

$this->title = 'Создание опроса';
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', ['poll' => $poll, 'pollOptions' => $pollOptions]) ?>
    </div>
</div>

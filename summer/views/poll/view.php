<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $poll->title;
?>
<div class="poll-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $poll->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>




    <div class="well well-sm">
        <h4><?= $poll->title ?></h4>

        <hr>

        <?php $form = ActiveForm::begin(); ?>

        <?php foreach($poll->pollOptions as $pollOption): ?>
            <?= $form->field($pollOption, 'name')->checkboxList(['1' => 11, '2' => 22]) ?>
        <?php endforeach ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>

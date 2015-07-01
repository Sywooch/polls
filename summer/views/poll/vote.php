<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\PollVoteCheckbox;

$this->title = 'Опрос "' . $pollVote->title . '""';
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well well-sm">
            <h1 class="text-center"><?= Html::encode($pollVote->title) ?></h1>

            <hr>

            <?php $form = ActiveForm::begin() ?>

            <?php if ($pollVote instanceof PollVoteCheckbox): ?>
                <?= $form->field($pollVote, 'voteResults')->checkboxList($pollVote->formattedOptions)->label(false) ?>
            <?php else: ?>
                <?= $form->field($pollVote, 'voteResults')->radioList($pollVote->formattedOptions)->label(false) ?>
            <?php endif ?>

            <hr>

            <div class="form-group">
                <?= Html::submitButton('Проголосовать', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Результаты', ['poll/view', 'id' => $pollVote->id], ['class' => 'btn btn-info']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

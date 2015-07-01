<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\PollVoteCheckbox;

$this->title = 'Опрос: ' . $pollVote->title;
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h1><?= Html::encode($pollVote->title) ?></h1>

        <?php $form = ActiveForm::begin(); ?>

        <?php if ($pollVote instanceof PollVoteCheckbox): ?>
            <?= $form->field($pollVote, 'voteResults')->checkboxList($pollVote->formattedOptions) ?>
        <?php else: ?>
            <?= $form->field($pollVote, 'voteResults')->radioList($pollVote->formattedOptions) ?>
        <?php endif ?>

        <div class="form-group">
            <?= Html::submitButton('Проголосовать', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

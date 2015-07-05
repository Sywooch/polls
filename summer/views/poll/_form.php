<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Poll;
?>
<?php $form = ActiveForm::begin(['id' => 'create-poll', 'action' => ['poll/create']]); ?>

<div class="well well-sm">
    <?= $form->field($poll, 'title')->textInput(['maxlength' => true, 'rows' => 4]) ?>

    <?= $form->field($poll, 'type')->dropDownList([
        POLL::TYPE_CHECKBOX => 'Выбор нескольких вариантов',
        POLL::TYPE_RADIO => 'Выбор одного варианта'
    ]) ?>

    <?= $form->field($poll, 'is_results_visible')->dropDownList(['Видны только вам', 'Видны всем']) ?>
</div>


<div class="poll-options">
    <?php foreach ($pollOptions as $index => $pollOption): ?>
        <?= $form->field($pollOption, "[$index]name", [
            'inputOptions' => ['class' => 'form-control input-sm'],
            'template' => '<div class="input-group">{input}<div class="input-group-btn"><button type="button" class="btn btn-sm btn-default poll-option-drag-btn"><span class="glyphicon glyphicon-resize-vertical"></span></button><button type="button" class="btn btn-sm btn-default pol-option-remove"><span class="glyphicon glyphicon-remove"></span></button></div></div><div class="help-block">{error}</div>'
        ])->textInput(['maxlength' => true, 'placeholder' => $pollOption->getAttributeLabel('name')])->label(false) ?>
    <?php endforeach ?>
</div>


<div class="form-group">
    <button type="button" class="btn btn-primary" id="add-poll-option">Добавить вариант</button>
    <?= Html::submitButton('Создать опрос', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

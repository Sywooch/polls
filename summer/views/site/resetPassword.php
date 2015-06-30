<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сброс пароля';
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well well-sm">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <hr>

            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        'label' => 'col-md-2',
                        'offset' => 'col-md-offset-2',
                        'wrapper' => 'col-md-10',
                        'error' => '',
                        'hint' => '',
                    ]
                ]
            ]) ?>

            <?= $form->field($resetPasswordForm, 'password')->passwordInput()->hint('Введите новый пароль.') ?>
            <div class="form-group row">
                <div class="col-md-10 col-md-offset-2">
                    <?= Html::submitButton('Сбросить пароль', ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

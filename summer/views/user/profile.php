<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Изменение профиля ' . ($id !== Yii::$app->user->identity->id ? (' ' . $profileForm->email) : '');
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
                        'label' => 'col-sm-2',
                        'offset' => 'col-sm-offset-2',
                        'wrapper' => 'col-sm-10',
                        'error' => '',
                        'hint' => '',
                    ]
                ]
            ]) ?>


                <?= $form->field($profileForm, 'email')->textInput() ?>
                <?= $form->field($profileForm, 'password')->passwordInput()->hint('Оставьте поле пустым, если не хотите менять пароль.') ?>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <?= Html::submitButton('Изменить профиль', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

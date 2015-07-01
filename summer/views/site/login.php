<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
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

            <?= $form->field($loginForm, 'email') ?>
            <?= $form->field($loginForm, 'password')->passwordInput() ?>
            <?= $form->field($loginForm, 'rememberMe')->checkbox([
                'template' => "{beginWrapper}\n<div class=\"checkbox\">" . Html::a('Забыли пароль?', ['site/request-password-reset'], ['class' => 'pull-right']) . "\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n</div>\n{error}\n{endWrapper}\n{hint}"
            ]) ?>
            <div class="form-group">
                <div class="col-md-10 col-md-offset-2">
                    <?= Html::submitButton('Вход', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

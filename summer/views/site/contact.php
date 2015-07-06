<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\captcha\Captcha;

$this->title = 'Обратная связь';
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
            <?= Alert::widget([
                'options' => [
                    'class' => 'alert-success',
                ],
                'body' => Yii::$app->session->getFlash('contactFormSubmitted')
            ]) ?>
        <?php endif ?>

        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <p>Если у вас есть вопросы или предложения, вы можете связаться со мной через обратную связь.</p>

        <?php $form = ActiveForm::begin() ?>

            <?= $form->field($contactForm, 'email') ?>
            <?= $form->field($contactForm, 'subject') ?>
            <?= $form->field($contactForm, 'body')->textArea(['rows' => 6]) ?>
            <?= $form->field($contactForm, 'captcha')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-xs-5 col-sm-3 col-md-2">{image}</div><div class="col-xs-7 col-sm-9 col-md-10">{input}</div></div>',
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>

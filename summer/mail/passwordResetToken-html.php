<?php
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/password-reset', 'token' => $user->password_reset_token]);
?>
<p>Здравствуйте, <?= Html::encode($user->email) ?>.</p>

<p>Перейдите по ссылке ниже для сброса пароля:</p>

<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

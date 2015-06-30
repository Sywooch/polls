<?php
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/password-reset', 'token' => $user->password_reset_token]);
?>
Здравствуйте, <?= $user->email ?>.

Перейдите по ссылке ниже для сброса пароля:

<?= $resetLink ?>

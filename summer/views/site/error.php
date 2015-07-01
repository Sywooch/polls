<?php
use yii\helpers\Html;

$this->title = 'Ошибка - ' . $exception->statusCode;
?>
<h1 class="text-center"><?= Html::encode($this->title) ?></h1>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>
    </div>
</div>

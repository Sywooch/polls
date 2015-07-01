<?php
use yii\helpers\Html;

$this->title = 'Результаты опроса "' . $poll->title . '"';
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="well well-sm">
            <h1 class="text-center"><?= Html::encode($poll->title) ?></h1>

            <hr>

            <ul class="list-group">
                <?php foreach ($poll->pollOptions as $option): ?>
                    <li class="list-group-item<?= $option->people_count === $maxPeopleCount ? ' list-group-item-success' : '' ?>">
                        <?= $option->name ?>
                        <span class="badge"><?= $option->people_count ?></span>
                    </li>
                <?php endforeach ?>
            </ul>

            <hr>

            <div class="form-group">
                <?= Html::a('Вернуться к голосованию', ['poll/vote', 'id' => $poll->id], ['class' => 'btn btn-info']) ?>
            </div>
        </div>
    </div>
</div>

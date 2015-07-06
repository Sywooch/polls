<?php
use yii\helpers\Html;
?>
<ul class="nav nav-tabs">
    <li role="presentation"<?= $currentPage === 'index' ? ' class="active"' : '' ?>>
        <?= Html::a('Общая информация', ['index']) ?>
    </li>
    <li role="presentation"<?= $currentPage === 'users-control' ? ' class="active"' : '' ?>>
        <?= Html::a('Управление пользователями', ['users-control']) ?>
    </li>
    <li role="presentation"<?= $currentPage === 'polls-control' ? ' class="active"' : '' ?>>
        <?= Html::a('Управление опросами', ['polls-control']) ?>
    </li>
</ul>

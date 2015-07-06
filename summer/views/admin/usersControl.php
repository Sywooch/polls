<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\helpers\Html;

$this->title = 'Панель администрирования - Управление пользователями'
?>
<?= $this->render('_nav', ['currentPage' => 'users-control']) ?>


<?php Pjax::begin(['timeout' => 5000]) ?>

    <?= GridView::widget([
        'dataProvider' => $usersDataProvider,
        'options' => ['class' => 'grid-view table-responsive'],
        'filterModel' => $userSearch,
        'tableOptions' => ['class' => 'table table-striped table-bordered table-condensed'],
        'layout' => "{summary}\n{items}\n<div class='text-center'>{pager}</div>",
        'pager' => [
            'options' => ['class' => 'pagination pagination-sm']
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'email',
            'created_at',
            'updated_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['user/profile', 'id' => $model->id]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['user/delete', 'id' => $model->id], ['data-method' => 'post', 'data-confirm' => 'Вы уверены, что хотите удалить этого пользователя?']);
                    }
                ]
            ]
        ]
    ]) ?>

<?php Pjax::end() ?>

<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Poll;
use yii\grid\ActionColumn;
use yii\helpers\Html;

$this->title = 'Панель администрирования - Управление опросами'
?>
<?= $this->render('_nav', ['currentPage' => 'polls-control']) ?>



    <?= GridView::widget([
        'dataProvider' => $pollsDataProvider,
        'options' => ['class' => 'grid-view table-responsive'],
        'filterModel' => $pollSearch,
        'tableOptions' => ['class' => 'table table-striped table-bordered table-condensed'],
        'layout' => "{summary}\n{items}\n<div class='text-center'>{pager}</div>",
        'pager' => [
            'options' => ['class' => 'pagination pagination-sm']
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'userEmail',
                'value' => function ($model, $key, $index, $column) {
                    return $model->user !== null ? $model->user->email : 'Гость';
                }
            ],
            'title',
            [
                'attribute' => 'pollOptionName',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    return '<span type="button" class="btn btn-sm btn-default center-block" data-toggle="popover" data-placement="auto top" data-content="' . Html::encode($model->pollOptionNames) .'">Показать варианты</span>';
                }
            ],
            [
                'attribute' => 'type',
                'value' => function ($model, $key, $index, $column) {
                    if ($model->type === Poll::TYPE_CHECKBOX) {
                        return 'Выбор из нескольких вариантов';
                    } else if ($model->type === Poll::TYPE_RADIO) {
                        return 'Выбор из одного варианта';
                    }
                }
            ],
            [
                'attribute' => 'is_results_visible',
                'value' => function ($model, $key, $index, $column) {
                    return ['Видны только вам', 'Видны всем'][$model->is_results_visible];
                }
            ],
            'people_count',
            'created_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {delete}',
                'controller' => 'poll'
            ]
        ]
    ]) ?>



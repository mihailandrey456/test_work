<?php

/** @var yii\web\View $this */

use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use common\widgets\Alert;

$this->title = 'Apples';
?>
<div class="site-index">
    <?php $form = ActiveForm::begin([
        'action' => ['apple/generate'],
    ]); ?>
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?php echo Html::submitButton('Сгенерировать яблоки', ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'color',
            [
                'attribute' => 'size',
                'value' => function ($data) {
                    return strval($data->size * 100) . '%';
                },
            ],
            [
                'attribute' => 'status',
                'value' => function($data) {
                    return $data->intStatusToString($data->status);
                },
            ],
            [
                'header' => 'Гнилое',
                'value' => function($data) {
                    return $data->isRotten() ? 'Да' : 'Нет';
                },
            ],
            [
                'attribute' => 'birthdayTime',
                'value' => function($data) {
                    return date('Y-m-d H:i:s', $data->birthdayTime);
                },
            ],
            [
                'attribute' => 'fallingTime',
                'value' => function($data) {
                    return !is_null($data->fallingTime)
                        ? date('Y-m-d H:i:s', $data->fallingTime)
                        : $data->intStatusToString($data->status);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'controller' => 'apple',
                'buttons' => [
                    'eat' => function ($url, $model, $key) {
                        return !$model->isRotten() && !$model->isOnTree()
                            ? Html::a('Съесть', $url)
                            : '';
                    },
                    'shake-tree' => function($url, $model, $key) {
                        return $model->isOnTree() ? Html::a('Потрясти дерево', $url) : '';
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('Удалить', $url, ['data-method' => 'post']);
                    },
                ],
                'template' => '{eat} {shake-tree} {delete}',
            ],
        ],
    ]); ?>
</div>

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
            'color:text:Цвет',
            [
                'attribute' => 'size',
                'label' => 'Сколько осталось от яблока',
                'value' => function ($data) {
                    return strval($data->size * 100) . '%';
                },
            ],
            [
                'attribute' => 'status',
                'label' => 'Статус',
                'value' => function($data) {
                    return $data->intStatusToString($data->status);
                },
            ],
            [
                'label' => 'Гнилое',
                'value' => function($data) {
                    return $data->isRotten() ? 'Да' : 'Нет';
                },
            ],
            [
                'attribute' => 'birthdayTime',
                'label' => 'Дата появления',
                'value' => function($data) {
                    return date('Y-m-d H:i:s', $data->birthdayTime);
                },
            ],
            [
                'attribute' => 'fallingTime',
                'label' => 'Дата падения',
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
                    'shake-tree' => function($url, $model, $key) {
                        return $model->isOnTree() ? Html::a('Потрясти дерево', $url) : '';
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('Удалить', $url, ['data-method' => 'post']);
                    },
                ],
                'template' => '{shake-tree} {delete}',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Съесть',
                'controller' => 'apple',
                'buttons' => [
                    'eat-25' => function ($url, $model, $key) {
                        $url = "/index.php?r=apple/eat&id=$key&precent=25";
                        return !$model->isRotten() && !$model->isOnTree()
                            ? Html::a('25%', $url)
                            : '';
                    },
                    'eat-50' => function ($url, $model, $key) {
                        $url = "/index.php?r=apple/eat&id=$key&precent=50";
                        return !$model->isRotten() && !$model->isOnTree()
                            ? Html::a('50%', $url)
                            : '';
                    },
                    'eat-75' => function ($url, $model, $key) {
                        $url = "/index.php?r=apple/eat&id=$key&precent=75";
                        return !$model->isRotten() && !$model->isOnTree()
                            ? Html::a('75%', $url)
                            : '';
                    },
                    'eat-100' => function ($url, $model, $key) {
                        $url = "/index.php?r=apple/eat&id=$key&precent=100";
                        return !$model->isRotten() && !$model->isOnTree()
                            ? Html::a('100%', $url)
                            : '';
                    },
                ],
                'template' => '{eat-25} {eat-50} {eat-75} {eat-100}',
            ],
        ],
    ]); ?>
</div>

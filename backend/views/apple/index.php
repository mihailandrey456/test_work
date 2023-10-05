<?php

/** @var yii\web\View $this */

use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

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
        'dataProvider' => $dataProvider
    ]); ?>
</div>

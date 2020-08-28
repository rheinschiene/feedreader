<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\feed\FeedDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="feed-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'feed_list_id') ?>

    <?= $form->field($model, 'url') ?>

    <?= $form->field($model, 'headline') ?>

    <?= $form->field($model, 'teaser') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'active') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

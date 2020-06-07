<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\feed\FeedList */
/* @var $form yii\widgets\ActiveForm */

$typeList=[ 0 => 'feed', 1 => 'tvshow' ];
$filterTypeList=[ 0 => 'skip_filter', 1 => 'keep_filter' ];
?>

<div class="feed-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'type')->dropDownList($typeList, ['prompt'=>'Select...']) ?>
    
    <?= $form->field($model, 'filter_type')->dropDownList($filterTypeList, ['prompt'=>'Select...']) ?>

    <?= $form->field($model, 'xpathHeadline')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'xpathTeaser')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'xpathContent')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

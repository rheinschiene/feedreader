<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\feed\FeedList;

/* @var $this yii\web\View */
/* @var $model app\models\feed\FeedFilter */
/* @var $form yii\widgets\ActiveForm */

$feedList=FeedList::find()->all();
$listData=ArrayHelper::map($feedList,'ID','name');

$typeList=[ 0 => 'url', 1 => 'headline', 2 => 'teaser', 3 => 'content' ];
?>

<div class="feed-filter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'feed_list_id')->dropDownList($listData, ['prompt'=>'Select...']) ?>

    <?= $form->field($model, 'type')->dropDownList($typeList, ['prompt'=>'Select...']) ?>

    <?= $form->field($model, 'expression')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

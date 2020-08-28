<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\feed\FeedData */

$this->title = 'Update Feed Data: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Feed Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="feed-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\feed\FeedList */

$this->title = 'Update Feed List: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Feed Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="feed-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

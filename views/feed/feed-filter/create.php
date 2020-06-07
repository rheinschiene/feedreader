<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\feed\FeedFilter */

$this->title = 'Create Feed Filter';
$this->params['breadcrumbs'][] = ['label' => 'Feed Filters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feed-filter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

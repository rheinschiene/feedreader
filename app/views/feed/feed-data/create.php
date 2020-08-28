<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\feed\FeedData */

$this->title = 'Create Feed Data';
$this->params['breadcrumbs'][] = ['label' => 'Feed Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feed-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

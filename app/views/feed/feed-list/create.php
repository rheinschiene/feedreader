<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\feed\FeedList */

$this->title = 'Create Feed List';
$this->params['breadcrumbs'][] = ['label' => 'Feed Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feed-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

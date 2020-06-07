<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\feed\FeedFilterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feed Filters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feed-filter-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Feed Filter', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            	['class' => 'yii\grid\SerialColumn'],
		'feedList.name',
	        'type',
        	'expression',
	        ['class' => 'yii\grid\ActionColumn'],
	],
    ]); ?>


</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\feed\FeedListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feed Lists';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feed-list-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Feed List', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'url:url',
    		[
    			'label' => 'Last Update',
    			'format' => 'text',
    			'value' => function($data) {
    				return $data['lastUpdate'];
    			}
    		],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

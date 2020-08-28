<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\feed\FeedDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feed Datas';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/feed.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="feed-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Feed Data', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'feedList.name',
            'url:url',
            'headline',
            'teaser',
            // 'content:ntext',
            [ 
                'label' => 'Date',
                'attribute' => 'date',
                'value' => function ($model) {
                    return $model->formatteddate;
                }
            ],
            // 'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

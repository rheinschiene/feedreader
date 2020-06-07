<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\feed\FeedDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feed Reader';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/reader.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>


<div class="panel panel-primary">
<div class="panel-heading">Reader</div>
<div class="panel-button" style="text-align: right; margin-right: 10px; margin-top: 10px;">
	<a class="btn btn-default" href="#" id="btnStar">Star</a>
	<a class="btn btn-default" href="#" id="btnGet">Get</a>
	<a class="btn btn-default" href="#" id="btnSync">Sync</a>
	<a class="btn btn-default" href="#" id="btnPrevious">Previous</a>
	<a class="btn btn-success" href="#" id="btnNext">Next</a>
</div>
<div class="panel-body">           
	<div class="col-lg-12">
		<table class="table table-striped table-bordered detail-view">
		<tbody>
			<tr><th id="tableHeadline">Headline</th></tr>
			<tr><td id="tableDate">Teaser</td></tr>
		</tbody>
		</table>
	</div>
				
	<div class="col-lg-12">
		<table class="table table-striped table-bordered detail-view">
		<tbody>
			<tr><th id="tableTeaser">Content</th></tr>
			<tr><td id="tableContent">Content</td></tr>
		</tbody>
		</table>	
	</div>
</div>
<div class="panel-footer">
	<p>All: <span id="allRecords"></span> | Active: <span id="activeRecords"></span> | Sync: <span id="syncRecords"></span> | Delete: <span id="canDeleteRecords"></span></p>
	<p id="ajaxStatus"></p>
	<p id="btnReset">RESET</p>
</div>
</div>	


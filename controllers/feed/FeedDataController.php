<?php

namespace app\controllers\feed;

use Yii;
use app\models\feed\FeedData;
use app\models\feed\FeedDataSearch;
use app\models\feed\FeedStars;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FeedDataController implements the CRUD actions for FeedData model.
 */
class FeedDataController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all FeedData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FeedDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStars()
    {
        $searchModel = new FeedDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('stars', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReader()
    {
        return $this->render('reader');
    }

    /**
     * Displays a single FeedData model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FeedData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FeedData();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FeedData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSetInactive()
    {

	$data = Yii::$app->request->post();

	if(empty($data)) {
		return $this->asJson([ 'count' => 0 ]);
	}

	foreach($data['syncArray'] as $value) {

		if(!is_numeric($value)) {
			return $this->asJson(['error' => 'Invalid ID']);
		}
	
		$model = $this->findModel($value);
		$model->active = 0;
		$model->save();
	}

        return $this->asJson([ 'count' => count($data['syncArray']) ]);
    }

    public function actionSetStar($id)
    {
	if(!is_numeric($id)) {
		return $this->asJson(['error' => 'Invalid ID']);
	}

	$model = new FeedStars();
	$model->feed_data_id = $id;
	if($model->save()) {
		return $this->asJson([ 'result' => 'marked' ]);
	}
        return $this->asJson([ 'result' => 'failure during set star' ]);
    }

    public function actionGetContent($startID)
    {

	if(!is_numeric($startID)) {
		return $this->asJson(['error' => 'Invalid startID']);
	}

	if(isset($startID)) {
		$data = FeedData::find()->where(['active' => 1])->andWhere(['>', 'ID', $startID])->orderBy([ 'ID' => SORT_ASC ])->limit(25)->all();
	}
	else {
		$data = FeedData::find()->where(['active' => 1])->orderBy([ 'ID' => SORT_ASC ])->limit(25)->all();
	}

	$resultArray = array();
	foreach($data as $value) {
		$resultArray[] = [ 'id' => $value->ID, 'headline' => $value->headline, 'teaser' => $value->teaser, 'content' => $value->content, 'date' => $value->formatteddate, 'source' => $value->feedList->name, 'url' => $value->url, 'active' => $value->active, 'canDelete' => 0 ];
	}

        return $this->asJson($resultArray);
    }

    /**
     * Deletes an existing FeedData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteStar($id)
    {
        $model = FeedStars::find()->where([ 'feed_data_id' => $id ])->one();
	$model->delete();

        return $this->redirect(['stars']);
    }

    /**
     * Finds the FeedData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FeedData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeedData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

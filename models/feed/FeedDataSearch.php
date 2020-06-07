<?php

namespace app\models\feed;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\feed\FeedData;

/**
 * FeedDataSearch represents the model behind the search form about `app\models\feed\FeedData`.
 */
class FeedDataSearch extends FeedData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'feed_list_id', 'active'], 'integer'],
            [['url', 'headline', 'teaser', 'content', 'date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $showStars = false)
    {
        $query = FeedData::find();

	if($showStars === false) {
		//$query->where([ 'active' => 1 ]);
	}
	else if($showStars === true) {
		$query->joinWith('feedStars', true, 'RIGHT JOIN');
	}

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'feed_list_id' => $this->feed_list_id,
            'date' => $this->date,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'headline', $this->headline])
            ->andFilterWhere(['like', 'teaser', $this->teaser])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}

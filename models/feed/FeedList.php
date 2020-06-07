<?php

namespace app\models\feed;

use Yii;

/**
 * This is the model class for table "feed_list".
 *
 * @property integer $ID
 * @property string $name
 * @property string $url
 */
class FeedList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feed_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url', 'type', 'filter_type'], 'required'],
            [['name'], 'string', 'max' => 30],
            [['url'], 'string', 'max' => 255],
            [['type', 'filter_type'], 'integer'],
	    [['xpathHeadline', 'xpathTeaser', 'xpathContent'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'type' => 'Type',
        ];
    }

    public function getFilter()
    {
        return $this->hasMany(FeedFilter::className(), ['feed_list_id' => 'ID']);
    }

    public function getLastUpdate()
    {
		$feedData = FeedData::find()->select('date')->where([ 'feed_list_id' => $this->ID ])->orderBy([ 'date' => SORT_DESC ])->one();
		return substr($feedData->date, 0, 10);
    }

}

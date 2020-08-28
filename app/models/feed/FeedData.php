<?php

namespace app\models\feed;

use Yii;

/**
 * This is the model class for table "feed_data".
 *
 * @property integer $ID
 * @property integer $feed_list_id
 * @property string $url
 * @property string $headline
 * @property string $teaser
 * @property string $content
 * @property string $date
 * @property integer $active
 */
class FeedData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feed_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['feed_list_id', 'url', 'headline', 'teaser', 'content', 'date', 'last_date', 'active'], 'required'],
            [['feed_list_id', 'active'], 'integer'],
            [['content'], 'string'],
            [['date', 'last_date'], 'safe'],
            [['teaser'], 'string', 'max' => 1000],
            [['headline', 'url'], 'string', 'max' => 255],
            [['url'], 'unique', 'targetAttribute' => ['url', 'feed_list_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'feed_list_id' => 'Feed List ID',
            'url' => 'Url',
            'headline' => 'Headline',
            'teaser' => 'Teaser',
            'content' => 'Content',
            'date' => 'Date',
            'last_date' => 'Last Date',
            'active' => 'Active',
        ];
    }

    public function getFeedList()
    {
        return $this->hasOne(FeedList::className(), ['id' => 'feed_list_id']);
    }

    public function getFeedStars()
    {
        return $this->hasOne(FeedStars::className(), ['feed_data_id' => 'ID']);
    }
    
    public function getFormattedDate() {
        $UTC = new \DateTimeZone("UTC");
        $newTZ = new \DateTimeZone("Europe/Berlin");
        $date = new \DateTime( $this->date, $UTC );
        $date->setTimezone( $newTZ );
        return $date->format('Y-m-d H:i:s');
    }
}

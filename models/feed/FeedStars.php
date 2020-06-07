<?php

namespace app\models\feed;

use Yii;

/**
 * This is the model class for table "feed_stars".
 *
 * @property int $ID
 * @property int $feed_data_id
 */
class FeedStars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feed_stars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['feed_data_id'], 'required'],
            [['feed_data_id'], 'integer'],
	    [['feed_data_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'feed_data_id' => 'Feed Data ID',
        ];
    }
}

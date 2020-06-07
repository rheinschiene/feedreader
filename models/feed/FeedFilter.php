<?php

namespace app\models\feed;

use Yii;

/**
 * This is the model class for table "feed_filter".
 *
 * @property int $ID
 * @property int $feed_list_id
 * @property int $type
 * @property string $expression
 */
class FeedFilter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feed_filter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['feed_list_id', 'type', 'expression'], 'required'],
            [['feed_list_id', 'type', 'expression_type'], 'integer'],
            [['expression'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'feed_list_id' => 'Feed List ID',
            'type' => 'Type',
            'expression' => 'Expression',
        ];
    }

    public function getFeedList()
    {
        return $this->hasOne(FeedList::className(), ['id' => 'feed_list_id']);
    }

}

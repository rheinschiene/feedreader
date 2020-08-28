<?php

namespace app\models\feed;

use Yii;

/**
 * This is the model class for table "feed_stars".
 *
 * @property int $ID
 * @property int $feed_data_id
 */
class UrlHelper extends \yii\db\ActiveRecord
{

	public $url;
	public $protocol_version = "1.1";
	public $method = "GET";
	public $header = array('Connection: close');
	public $user_agent = "RSS Crawler";
	public $content;
	public $url_info;
	private $context;

	function __construct($url) {
    	$this->url = $url;
		$this->url_info = parse_url($this->url);

		$opts = array(
			'http' => array(
				'protocol_version' => $this->protocol_version,
				'method' => $this->method,
				'header' => $this->header,
				'user_agent' => $this->user_agent,
			 )
		);

		$this->context = stream_context_create($opts);

  	}
	
    public function get_contents()
    {

		try {
			$temp = @file_get_contents($this->url, false, $this->context);
			$this->content = mb_convert_encoding($temp, 'HTML-ENTITIES', "UTF-8");
		}
		catch (Throwable $exception) {
			echo $exception->getMessage();
			return false;
		}

		return true;
    }
}

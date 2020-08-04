<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\feed\FeedList;
use app\models\feed\FeedData;
use app\models\feed\FeedStars;
use app\models\feed\UrlHelper;

class FeedController extends Controller
{

    public function actionIndexAll($type = 0)
    {
        $liste = FeedList::find()->all();
	
        foreach($liste as $key => $value)
        {
            if($value->type == $type) {
                $this->actionGetLinks($value->ID);
            }
        }
    }

	public function actionGetLinks($feedListID) {

		$feedList = FeedList::findOne($feedListID);

		$url = new UrlHelper($feedList->url);

		if(!$url->get_contents()) {
			echo "Could not load $url \n";
			return false;
		}		

		$dom = new \DOMDocument();
		@$dom->loadHTML($url->content);
		$xpath = new \DOMXPath($dom);
		$links = $xpath->evaluate("/html/body//a");

		for($i = 0; $i < $links->length; $i++){

			$href = $links->item($i);

			$linkUrl = trim($href->getAttribute('href'));

			if(substr($linkUrl, 0, 1) == "/" && substr($linkUrl, 1, 1) != "/") {
				$temp = $url->url_info['scheme'] . "://" . $url->url_info['host'] . $linkUrl;
				$linkUrl = $temp;
			}

			$linkUrl = filter_var($linkUrl, FILTER_SANITIZE_URL);
			echo "\n" . $linkUrl;

			if(empty($linkUrl) || substr($linkUrl, 0, 1) == "#") {
				echo " => skip (#)";
				continue;
			}

			if (!filter_var($linkUrl, FILTER_VALIDATE_URL)) {
				echo " => skip (not valid)";
				continue;
			}

			//filter_type 0 = skip_list => URLs skippen
		    //filter_type 1 = keep_list => URL verwenden
			// Handelt es sich um eine Skip Liste?
			if($feedList->filter_type == 0) {
				// Die linkUrl muss die URL der Seite enthalten. Also z.B. golem.de/xyz, tagesschau.de/xyz, aber nicht facebook.de usw.
				if(!(strpos($linkUrl, $feedList->url) !== false)) {
					echo " => skip (url must contain baseURL)";
					continue;
				}

				foreach($feedList->filter as $badUrl) {
					if($badUrl->type == 0) {
						if(strpos($linkUrl, $badUrl->expression) !== false) {
							echo " => skip (badUrl filter)";
				        	continue 2;
						}
					}
				}

			}

			// Handelt es sich um eine Keep Liste?
			if($feedList->filter_type == 1) {
				$result_found = false;
				foreach($feedList->filter as $badUrl) {
					if($badUrl->type == 0) {
						if(strpos($linkUrl, $badUrl->expression) !== false) {
				            // Wenn der filter_type 1 (keep_list) ist, dann soll die Filterung abgebrochen werden und die URL verwendet werden.
				            if($feedList->filter_type == 1) {
				                $result_found = true;
								echo " => keep (match)";
				                break;
				            }
				            // Ansonsten den ganzen Vorgang abbrechen und die nächste URL nehmen.
				            else {
								echo " => skip123 (keeplist badUrl)";
				                continue 2;
				            }
						}
					}
				}

				if($result_found === false) {
				    // Es wird eine keep_list verwendet und es wurde kein Ergebnis gefunden...
				    // Verarbeitung dieser URL abbrechen und mit der Nächsten fortfahren.
					echo " => skip (keeplist no result)";
				    continue;
				}
			}

			$data = FeedData::find()->where([ 'url' => $linkUrl ])->andWhere([ 'feed_list_id' => $feedList->ID ])->one();
			if(empty($data)) {
				// Die URL ist neu... den Artikel einlesen...
				$data = new FeedData();
				$data->url = $linkUrl;
				$data->active = 1;
				$data->date = date("Y-m-d H:i:s");
				$data->last_date = date("Y-m-d H:i:s");
				$data->feed_list_id = $feedList->ID;

				try {
					$result = $this->actionIndexSite($data, $feedList);
				}
				catch (Throwable $exception) {
					echo $exception->getMessage();
					continue;
				}

				if(!$result) {
					echo " [FAILED]";
					continue;
				}
				else {
					// Kommt der Titel bereits in alten Elementen vor? Duplicates verindern!
					$duplicate = FeedData::find()->where([ 'headline' => $data->headline ])->andWhere([ 'feed_list_id' => $feedList->ID ])->one();
					if(!empty($duplicate)) {
						echo " [DUPLICATE]";
						$data->active = 0;
					}
				}

			}
			else {
				echo " (updated last_date)";
				$data->last_date = date("Y-m-d H:i:s");
			}
			
			try {
				if(!$data->save()) {
					print_r($data->getErrors());
				}
			}
			catch (Throwable $exception){
					echo $exception->getMessage();
					echo "\Exception! Continue!\n";
					continue;
			}

		}
	}

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndexSite($data, $feedList)
    {

		$url = new UrlHelper($data->url);
		
		if(!$url->get_contents()) {
			echo "Could not load $url \n";
			return false;
		}

		if(strlen($url->content) < 200) {
			// Inhalt der Seite zu klein... Vermutlich redirect.
			echo "Content too small...\n";
			return false;
		}

		$dom = new \DOMDocument();
		@$dom->loadHTML($url->content);
		$xpath = new \DOMXPath($dom);

		$xpathHeadline = $feedList->xpathHeadline;
		$xpathTeaser = $feedList->xpathTeaser;
		$xpathContent = $feedList->xpathContent;

		$headline = $xpath->evaluate($xpathHeadline);
		if($headline->length > 0) {
			$i = 0;
			foreach($headline as $value) {
				$seperator = "";
				if($i < $headline->length - 1) {
					$seperator = " - ";
				}
				$data->headline .= $value->nodeValue . $seperator;
				$i++;
			}
		}
		else {
			$title = $xpath->evaluate('/html/head/title');
			if($title->length > 0) {
				$data->headline = $title->item(0)->nodeValue;
			}
		}
		
		$teaser = $xpath->evaluate($xpathTeaser);
		if($teaser->length > 0) {
			foreach($teaser as $value) {
				$data->teaser .= $value->nodeValue;
			}
		}
		
		if(!empty($xpathContent)) {
		    $nodes = $xpath->evaluate($xpathContent);
		    if($nodes->length < 2) {
		        $data->content = "-";
		    }
		    else {
		        foreach($nodes as $node) {
		            // Ist die Node selbst fett formatiert?
		            $nodeValue = $node->nodeValue;
		            $nodeValue = str_replace("ü","u_e",$nodeValue);
					$nodeValue = str_replace("Ü","U_e",$nodeValue);
					$nodeValue = str_replace("ö","o_e",$nodeValue);
					$nodeValue = str_replace("Ö","O_e",$nodeValue);
					$nodeValue = str_replace("ä","a_e",$nodeValue);
					$nodeValue = str_replace("Ä","A_e",$nodeValue);
		            $sanitized_nodeValue = filter_var($nodeValue, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
		            
		            if($node->tagName == "h1" || $node->tagName == "h2" || $node->tagName == "h3" || $node->tagName == "b" || $node->tagName == "strong") {
		                $data->content .= "<strong>" . trim($nodeValue) . "</strong>";
		            }
		            else {
		                $data->content .= "<p>" . trim($nodeValue) . "</p>";
		            }
		        }
		    }
		}
		else {
		    $data->content = "-";
		}
		
		// Kontrolle der Werte
		
		$data->headline = trim($data->headline);
		$data->teaser = trim($data->teaser);

		if(strlen($data->headline) > 255) {
			$data->headline = substr($data->headline,0,254);
		}
		if(strlen($data->headline) == 0) {
			echo "Headline is empty!\n";
			return false;
		}
		if(strlen($data->teaser) > 1000) {
			$data->teaser = substr($data->teaser,0,999);
		}

		if(strlen($data->teaser) == 0) {
			$data->teaser = "-";
		}
		
		return true;

    }

	public function actionCleanUp()
    {
		$count = 0;
		$now  = new \DateTime('now');
		$data =	FeedData::find()->orderBy([ 'last_date' => SORT_ASC ])->all();
		foreach($data as $value) {
			$UTC = new \DateTimeZone("UTC");
			$date = new \DateTime( $value->last_date, $UTC );
			$interval = $date->diff($now);
			if($interval->format('%a') > 365) {
				// Ist das Item noch auf der FeedStars Liste? Wenn ja, nicht loeschen!
				$stars = FeedStars::find()->where([ 'feed_data_id' => $value->ID ])->all();
				if(!empty($stars)) {
					echo "$value->ID Item ist noch in FeedStars Liste!\n";
					continue;
				}
				$value->delete();
				$count++;
			}
		}
		echo "$count items deleted!\n";
	}
}

<?php 

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 Christian Grlica
 *  Contact: christian.grlica@heavenseven.net
 *  All rights reserved
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 ***************************************************************/


$clientLibraryPath = t3lib_extMgm::extPath('youtubeapi') . '/zendgdata/library/';
$oldPath = set_include_path(get_include_path() . PATH_SEPARATOR . $clientLibraryPath);

require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
Zend_Loader::loadClass('Zend_Gdata_YouTube');

tx_div::load('tx_lib_controller');
tx_div::load('tx_lib_link');

class tx_youtubeapi_models_retrieve extends tx_lib_object {
	var $className = 'tx_youtubeapi_models_retrieve';
	var $extensionKey = 'tx_youtubeapi';
	
	// constructor
	function tx_youtubeapi_models_retrieve($controller = null, $parameter = null) {
	
		parent::tx_lib_object($controller,$parameter);
		
		// get data
		$this->baseUrl = $this->controller->configurations->get('baseURL');
		$this->developerKey = $this->controller->configurations->get('developerKey');
		$this->singlePage = $this->controller->configurations->get('singlePage');
		$this->maxResults = $this->controller->configurations->get('maxResults') ? $this->controller->configurations->get('maxResults') : 5;
		
		$this->orderBy = $this->controller->configurations->get('orderBy');
		$this->startIndex = 1;
		
		//$user = $this->controller->configurations->get('user');
		$this->channel = $this->controller->configurations->get('channel');
		$this->favorites = $this->controller->configurations->get('favorites');
		$this->searchTerm = $this->_searchable( $this->controller->configurations->get('searchTerm') );
		$this->category = $this->_categorized( $this->controller->configurations->get('category') );
		$this->keywords = $this->_keyworded( $this->controller->configurations->get('keywords') );
		$this->playlist = $this->controller->configurations->get('playList');
	   
	}
	
	public function load() {
  	$parameters = $this->controller->parameters;

  	// searchparameter (frontend)
  	if( $_REQUEST[tx_youtubeapi]['search'] ) $this->searchTerm = $_REQUEST[tx_youtubeapi]['search'];
  	if( $_REQUEST[tx_youtubeapi]['type'] == 'channel') $this->channel = $_REQUEST[tx_youtubeapi]['search'];
  	if( $_REQUEST[tx_youtubeapi]['type'] == 'keywords') $this->keywords = $_REQUEST[tx_youtubeapi]['search'];
  	if( $_REQUEST[tx_youtubeapi]['type'] == 'category') $this->category = $_REQUEST[tx_youtubeapi]['search'];
  	
    if( $_REQUEST[tx_youtubeapi]['maxResults'] ) $this->maxResults = $_REQUEST[tx_youtubeapi]['maxResults'];
  	if( $_REQUEST[tx_youtubeapi]['startIndex'] ) $this->startIndex = $_REQUEST[tx_youtubeapi]['startIndex'];
  	if( $_REQUEST[tx_youtubeapi]['orderBy'] ) $this->orderBy = $_REQUEST[tx_youtubeapi]['orderBy'];
    
    // build feedUrl
    if( !$this->baseUrl ) die('Please specify the baseUrl for the API (http://gdata.youtube.com/feeds/api/) in EXT:youtubeapi/configurations/setup.txt');
    
    $yt = new Zend_Gdata_YouTube();
    $query = $yt->newVideoQuery();	
    
    if($this->channel) {
      $videofeed = $yt->getUserUploads($this->channel); 
    } else {
      if($this->category && !$this->keywords) $query->category = $this->_categorized($this->category);  
      if($this->keywords && !$this->category) $query->keyword = $this->_keyworded($this->keywords);

      $videofeed = $yt->getVideoFeed($query); 
    }
    
    $query->orderBy = $this->orderBy;	 
    $query->videoQuery = $this->searchTerm;
    $query->startIndex = $this->startIndex;
    $query->maxResults = $this->maxResults;
   
		// prepare searchLink
    $link = tx_div::makeInstance('tx_lib_link');
    $link->designator($this->extensionKey);
    $link->destination($GLOBALS['TSFE']->id);
    $link->noHash();
    $linkparams = Array("search" => $this->get('searchTerm'));
    $link->parameters($linkparams);
    $this->set('searchLink',$link->makeUrl());
	
		// retrieve feed
    $feedUrl = $this->baseUrl . '/feeds/api/' .$append;
//		$result = $this->_getFeed($feedUrl);

//    print_r($yt->getVideoFeed($query));
    if($videofeed)
    {
        $result = $this->_getFeed($videofeed);
        $this->set('resultList', $result);
		}
  }
	
	private function _getFeed($videoFeed) {		
    $feed = tx_div::makeInstance('tx_lib_object');

    $c = 0;   
		foreach ($videoFeed as $entry) {
		
			$feedEntry = $this->_getVideoMetadata($entry);
			
			// single video
			if($c == 0) {
        $this->set('singleVideoUrl', $this->_findFlashUrl($entry));
        $this->set('singleMetaData', $feedEntry);
      }
      
			// crop descriptiontext on list
			$text = $feedEntry->get('description');
			$feedEntry->set('description', $this->_cropText($text));
      $feed->append($feedEntry);
  		$c++;
		}
		return $feed;
	}
	
	function _getVideoMetadata($entry) {
    
    $feedEntry = new tx_lib_object();
    $videoID = $entry->getVideoId();
    $authorUsername = $entry->author[0]->name;
    
    $feedEntry->set('title', $entry->getVideoTitle());
    $feedEntry->set('description', $entry->getVideoDescription());
    $feedEntry->set('thumbnail', $entry->mediaGroup->thumbnail[0]->url);
    $feedEntry->set('authorUrl', 'http://www.youtube.com/profile?user=' . $authorUsername);		  
    $feedEntry->set('author', $authorUsername);
    $feedEntry->set('keywords', $entry->mediaGroup->keywords);
    $feedEntry->set('duration', $this->_durationInMinutes($entry->mediaGroup->duration->seconds));
    $feedEntry->set('url', $entry->mediaGroup->player[0]->url);
    $feedEntry->set('viewCount', $entry->statistics->viewCount);
    $feedEntry->set('rating', $entry->rating->average);
    $feedEntry->set('numRaters', $entry->rating->numRaters);
    $feedEntry->set('flashUrl', $this->_findFlashUrl($entry));
    
    return $feedEntry;
  }
  
  function _showVideoPlayer($videoID) {
    $yt = new Zend_Gdata_YouTube();
    $entry = $yt->getVideoEntry($videoId);
    $metaData = $this->_getVideoMetaData($entry);
  
  }
  
  function _getRelatedVideos($videoId) {
      $yt = new Zend_Gdata_YouTube();
      $ytQuery = $yt->newVideoQuery();
      // show videos related to the specified video
      $ytQuery->setFeedType('related', $videoId);
      // order videos by rating
      $ytQuery->setOrderBy('rating');
      // retrieve a maximum of videos
      $ytQuery->setMaxResults(5);
      // retrieve only embeddable videos
      $ytQuery->setFormat(5);
      return $yt->getVideoFeed($ytQuery);
  }
  
  function _getComments($videoId) {
      $yt = new Zend_Gdata_YouTube();
      $ytQuery = $yt->newVideoQuery();
      // show videos related to the specified video
      $ytQuery->setFeedType('comments', $videoId);
      // order videos by rating
      $ytQuery->setOrderBy('rating');
      // retrieve a maximum of videos
      $ytQuery->setMaxResults(5);
      // retrieve only embeddable videos
      $ytQuery->setFormat(5);
      return $yt->getVideoFeed($ytQuery);
  }
  
  function _getTopRatedVideosByUser($user) {
      $userVideosUrl = $this->baseUrl . '/feeds/users/' . $user . '/uploads';
      $yt = new Zend_Gdata_YouTube();
      $ytQuery = $yt->newVideoQuery($userVideosUrl);  
      // order by the rating of the videos
      $ytQuery->setOrderBy('rating');
      // retrieve a maximum of videos
      $ytQuery->setMaxResults(5);
      // retrieve only embeddable videos
      $ytQuery->setFormat(5);
      return $yt->getVideoFeed($ytQuery);
  }

  function _findFlashUrl($entry) {
      foreach ($entry->mediaGroup->content as $content) {
          if ($content->type == 'application/x-shockwave-flash') {
              return $content->url;
          }
      }
      return null;
  }
  
  function _getAuthSubRequestUrl() {
      $next = 'http://www.example.com/welcome.php';
      $scope = $this->baseUrl;
      $secure = false;
      $session = true;
      return Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session);
  }

	
	function _getVideoId($url) {
	
    $parts = explode("v=",$url);
    return $parts[1];
    
  }
  
  function _searchable($terms) {
  
    return ereg_replace('[[:space:]]+', '+', trim($terms));
  
  }
  
  function _categorized($terms) {
    
    $temp = explode(" ", $terms);
    for($i = 0; $i < count($temp);$i++) {
      $temp[$i] = ucfirst($temp[$i]);
    }
    return implode("|", $temp);
    
  }
  
  function _keyworded($terms) {
  
    $terms = ereg_replace('[[:space:]]+', '/', trim($terms));
    return strtolower($terms);  
    
  }
  
  /*
  ** HELPER
  */
  
  
  function _durationInMinutes($length) {
		
    $min = intval($length / 60);
		$s = $length - ($min * 60);
		return "$min:$s min";
		
  }
  function _cropText($text) {
    if(strlen($text) > $this->controller->configurations->get('list.textCrop')) {
      $text = substr($text, 0, $this->controller->configurations->get('list.textCrop')) . '...';
    }
    return $text;
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/youtubeapi/models/class.tx_youtubeapi_models_retrieve.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/youtubeapi/models/class.tx_youtubeapi_models_retrieve.php']);
}
?>

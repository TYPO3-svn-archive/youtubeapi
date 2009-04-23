<?php
  if($this->get('singlePage')) echo "hier biste auf der singlePage";
  
?>  
  <div class="tx-youtubeapi-single">
    <div id="videoplayer">
    <?php   
         echo '<object width="'. $this->get('singlePlayerWidth') .'" height="'. $this->get('singlePlayerHeight') .'">
              <param name="movie" value="'. $this->get('singleVideoUrl'). '"></param>
              <param name="wmode" value="transparent"></param>
              <embed src="'. $this->get('singleVideoUrl'). '" type="application/x-shockwave-flash" wmode="transparent" width="'. $this->get('singlePlayerWidth') .'" height="'. $this->get('singlePlayerHeight') .'"></embed>
             </object>
       ';
    ?>
    </div>
    <div id="metadata">
    <?php
      $metaData = $this->get('singleMetaData');
      echo '
        <h2>'. $metaData->get('title') .'</h2>
        <p><a href="'. $metaData->get('authorUrl') .'" target="_blank">'. $metaData->get('author') .'</a></p>
        <p class="bodytext">'. $metaData->get('description') .'</p>
        <p>%%%fe_duration%%%: '. $metaData->get('duration') .'</p>
        <p>%%%fe_rating%%%: '. $metaData->get('rating') .'</p>
        <p>%%%fe_views%%%: '. $metaData->get('viewCount') .'</p>
        <p>%%%fe_keywords%%%: '. $metaData->get('keywords') .'</p>
      ';
    ?>
    </div>
  </div>
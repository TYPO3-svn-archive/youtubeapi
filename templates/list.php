
<div class="tx-youtubeapi">
  <div class="tx-youtubeapi-searchbox">
  <?php if($this->get('showSearchBox')): ?><!-- searchbox begin -->
  <form method="post" action="<?php echo $this->get('searchLink'); ?>">
      <span><input type="text" name="tx_youtubeapi[search]" value="<?php echo $this->get('searchTerm') ?>"/></span>
      
      <span>
        <select name="tx_youtubeapi[type]">
          <option value=""></option>
          <option value="keywords">%%%fe_keywords%%%</option>
          <option value="channel">%%%fe_channel%%%</option>
          <option value="category">%%%fe_category%%%</option>
        </select>
      </span>
      
      <span>%%%fe_maxResults%%%</span>
      <span>
        <select name="tx_youtubeapi[maxResults]">
          <option value=""></option>
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
      </span>
      
      <input type="submit" name="submit" value="%%%fe_submit%%%"/>  
    </form> 
    </div>
<!-- searchbox end --><?php endif; ?>	

<?php $entryList = $this->get('entryList'); ?>

<?php if($entryList->isNotEmpty()): ?>
<div class="tx-youtubeapi-list">
      <ul>    
       <?php if($this->has('totalResults')): ?>
         <li>
          <span>%%%fe_totalResults%%%<?php echo $this->get('totalResults'); ?><span>
         </li>
      <?php endif; ?>	
      
      <?php if($this->get('showPageBrowser')): ?><!-- pagebrowser begin -->
         <li>
          <?php echo '<span><a href="' . $this->get('prev') . '">%%%fe_prev%%%</a></span>&nbsp;<span><a href="' . $this->get('next') . '">%%%fe_next%%%</a></span>'; ?>
         </li>
    <!-- pagebrowser end --><?php endif; ?>	
        
  <?php endif; ?>
  
  <?php for($entryList->rewind(); $entryList->valid(); $entryList->next()): $entry = $entryList->current(); ?>
  	<li>
  	
  	<?php if($this->get('showEmbedded')): ?>
    	<h3><?php $entry->printAsText('title'); ?></h3>
      <?php   
    	   
         echo '<object width="'. $this->get('listPlayerWidth') .'" height="'. $this->get('listPlayerHeight') .'">
              <param name="movie" value="'. $entry->get('flashUrl'). '"></param>
              <param name="wmode" value="transparent"></param>
              <embed src="'. $entry->get('flashUrl'). '" type="application/x-shockwave-flash" wmode="transparent" width="'. $this->get('listPlayerWidth') .'" height="'. $this->get('listPlayerHeight') .'"></embed>
             </object>
       ';
       ?>
  	
  	<?php else : ?>
  	
  		<?php if($entry->has('thumbnail')): ?>
  			<p><?php echo '<a href="' . $entry->get('url') . '" target="_blank"><img src="' . $entry->get('thumbnail') .'" /></a>'; ?></p>
  		<?php endif; ?>
  		
      
      <?php if($entry->has('title')): ?>
  			<h3><?php $entry->printAsText('title'); ?></h3>
  		<?php endif; ?>
  		
  		<?php if($entry->has('author')): ?>
  			<p>%%%fe_by%%% <?php $entry->printAsText('author'); ?></p>
  		<?php endif; ?>
  		
  		<?php if($entry->has('description')): ?>
  		  <p class="bodytext"><?php $entry->printAsText('description'); ?></p>
  		<?php endif; ?>
  		
  		<?php if($entry->has('url')): ?>
  			<p><?php $entry->printAsUrl('url'); ?></p>
  		<?php endif; ?>
  		
  		
  		<?php if($entry->has('keywords')): ?>
  		  <?php
            $keywords = $entry->get('keywords'); 
            
        ?>
  			<p><?php echo $keywords; ?></p>
  		<?php endif; ?>
  		
      
      <?php if($entry->has('duration')): ?>
  			<p>%%%fe_duration%%%
  				<?php 
  					echo $entry->get('duration');   
  				?>
  			</p>
  		<?php endif; ?>
  		
  		<?php if($entry->has('viewCount')): ?>
  			<p>%%%fe_views%%%<?php $entry->printAsText('viewCount');   ?></p>
  		<?php endif; ?>
  		
  		<?php if($entry->has('favoriteCount')): ?>
  			<p>%%%fe_favorites%%%<?php $entry->printAsText('favoriteCount');   ?></p>
  		<?php endif; ?>
  		
  		<?php if($entry->has('rating')): ?>
  			<p>%%%fe_rating%%%<?php $entry->printAsText('rating'); ?></p>
  		<?php endif; ?>
  		
  		<?php if($entry->has('comments')): ?>
  			<p>%%%fe_comments%%%<?php $entry->printAsRaw('comments'); ?></p>
  		<?php endif; ?>
  	 	
  	<?php endif; ?>	
  	</li>
  
  <?php endfor; ?>
  
        <?php if($this->get('showPageBrowser')): ?><!-- pagebrowser begin -->
            <li>
             <?php echo '<span><a href="' . $this->get('prev') . '">%%%fe_prev%%%</a></span>&nbsp;<span><a href="' . $this->get('next') . '">%%%fe_next%%%</a></span>'; ?>
         </li>
        <!-- pagebrowser end --><?php endif; ?>	
  
  <?php if($entryList->isNotEmpty()): ?>
  	</ul>
    </div>
  <?php
    // if no singlePage is chosen display on same page
    if(!$this->get('singlePage')) include('single.php');
  ?>
</div>
<?php endif; ?>

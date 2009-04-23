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


class tx_youtubeapi_controllers_retrieve extends tx_lib_controller {
  
	var $defaultAction = 'mainAction';
	var $viewClassName = 'tx_lib_phpTemplateEngine';
	var $templateKey = 'list.php';
	var $entryViewClassName = 'tx_youtubeapi_views_list';
	

	function mainAction() {

		$modelClassName = tx_div::makeInstanceClassName('tx_youtubeapi_models_retrieve');
		$resultBrowserClassName = tx_div::makeInstanceClassName('tx_lib_resultBrowser');
	  $entryListClassName = tx_div::makeInstanceClassName($this->entryViewClassName);
		$entryClassName = tx_div::makeInstanceClassName($this->entryViewClassName);
		$templateEngineClassName = tx_div::makeInstanceClassName($this->viewClassName);
		$translatorClassName = tx_div::makeInstanceClassName('tx_lib_translator');
    
		$model = new $modelClassName($this);
		$model->load();
		$resultList = $model->get('resultList');
		$entryList = new $entryListClassName($this);
        
		for($resultList->rewind(); $resultList->valid(); $resultList->next()) {
			$entry = new $entryClassName($this,$resultList->current());
			$entryList->append($entry);
		}
		
		$templateEngine = new $templateEngineClassName($this, new $modelClassName($this));
		$templateEngine->set('entryList', $entryList);
    $templateEngine->set('prev', $model->get('prev')); 
    $templateEngine->set('searchLink', $model->get('searchLink'));
    $templateEngine->set('searchTerm', $model->get('searchTerm'));
    $templateEngine->set('next', $model->get('next'));
		$templateEngine->set('showEmbedded', $this->configurations->get('showEmbedded'));
		
    $templateEngine->set('singlePlayerHeight', $this->configurations->get('single.playerHeight'));
    $templateEngine->set('singlePlayerWidth', $this->configurations->get('single.playerWidth'));
		$templateEngine->set('singlePage', $this->configurations->get('single.singlePageUid'));
    
    $templateEngine->set('listPlayerWidth', $this->configurations->get('list.playerWidth'));
		$templateEngine->set('listPlayerHeight', $this->configurations->get('list.playerHeight'));
		
    $templateEngine->set('singleVideoUrl', $model->get('singleVideoUrl'));
		$templateEngine->set('singleMetaData', $model->get('singleMetaData'));
		
    $templateEngine->set('showPageBrowser', $this->configurations->get('showPageBrowser'));
		$templateEngine->set('showSearchBox', $this->configurations->get('showSearchBox'));
    $templateEngine->set('totalResults', $model->get('totalResults'));
		$templateEngine->render($this->templateKey);
		$translator = new $translatorClassName($this, $templateEngine);
		return $translator->translateContent();

	}
	

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/youtubeapi/controllers/class.tx_youtubeapi_controllers_retrieve.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/youtubeapi/controllers/class.tx_youtubeapi_controllers_retrieve.php']);
}
?>

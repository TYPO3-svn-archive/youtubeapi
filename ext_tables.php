<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addStaticFile('youtubeapi', 'configuration', 'Youtubeapi'); // ($extKey, $path, $title)
t3lib_extMgm::addPlugin(array('Youtubeapi', 'tx_youtubeapi'));  // array($title, $pluginKey)
t3lib_extMgm::addPiFlexFormValue('tx_youtubeapi', 'FILE:EXT:youtubeapi/configuration/flexform.xml'); // (pluginKey, path)
$TCA['tt_content']['types']['list']['subtypes_excludelist']['tx_youtubeapi']='layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist']['tx_youtubeapi']='pi_flexform';

?>

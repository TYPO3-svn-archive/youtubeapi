<?php

########################################################################
# Extension Manager/Repository config file for ext: "youtubeapi"
#
# Auto generated 28-01-2009 19:54
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Youtube API',
	'description' => 'Retrieves feeds via the Youtube API which can be listed in the frontend.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '0.7.0',
	'dependencies' => 'div,lib',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Christian Grlica',
	'author_email' => 'christian.grlica@heavenseven.net',
	'author_company' => 'heavenseven',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.0-0.0.0',
			'div' => '',
			'lib' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:14:{s:9:"ChangeLog";s:4:"0ea5";s:12:"ext_icon.gif";s:4:"fd00";s:17:"ext_localconf.php";s:4:"9fce";s:14:"ext_tables.php";s:4:"e6ef";s:13:"locallang.xml";s:4:"adcf";s:16:"locallang_db.xml";s:4:"c04b";s:26:"configuration/flexform.xml";s:4:"38af";s:23:"configuration/setup.txt";s:4:"425b";s:56:"controllers/class.tx_youtubeapi_controllers_retrieve.php";s:4:"9591";s:14:"doc/manual.pdf";s:4:"39c1";s:14:"doc/manual.sxw";s:4:"3591";s:46:"models/class.tx_youtubeapi_models_retrieve.php";s:4:"86a6";s:18:"templates/list.php";s:4:"2294";s:40:"views/class.tx_youtubeapi_views_list.php";s:4:"53fc";}',
	'suggests' => array(
	),
);

?>
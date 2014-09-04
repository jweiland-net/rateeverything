<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'JWeiland.' . $_EXTKEY,
	'Rate',
	array(
		'Rating' => 'show',
		'Ajax' => 'callAjaxObject',
	),
	// non-cacheable actions
	array(
		'Rating' => 'show',
		'Ajax' => 'callAjaxObject',
	)
);
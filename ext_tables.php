<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'JWeiland.' . $_EXTKEY,
	'Rate',
	'LLL:EXT:rateeverything/Resources/Private/Language/locallang_db.xlf:plugin.title'
);

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_rate'] = 'layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_rate'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY . '_rate', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Rating.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Rate everything');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/DefaultCSS', 'Default CSS');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_rateeverything_domain_model_rating', 'EXT:rateeverything/Resources/Private/Language/locallang_csh_tx_rateeverything_domain_model_rating.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_rateeverything_domain_model_rating');
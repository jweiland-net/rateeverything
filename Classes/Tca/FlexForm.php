<?php
namespace JWeiland\Rateeverything\Tca;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Stefan Froemken <projects@jweiland.net>, jweiland.net
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * @package rateeverything
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FlexForm {

	/**
	 * fills selectbox for allowed tables
	 *
	 * @param array $config This is the configuration array from TCA
	 * @return array
	 */
	public function fillSelectBox(array $config) {
		/** @var \JWeiland\Rateeverything\Configuration\ExtConf $extConf */
		$extConf = GeneralUtility::makeInstance('JWeiland\\Rateeverything\\Configuration\\ExtConf');
		$items = array();
		foreach ($extConf->getAllowedTables() as $table) {
			$label = LocalizationUtility::translate($GLOBALS['TCA'][$table]['ctrl']['title'], 'notNeeded');
			$label .= ' (' . $table . ')';
			$items[] = array($label, $table);
		}
		$config['items'] = array_merge($config['items'], $items);
		return $config;
	}

	/**
	 * get tt_content uid of previous content element
	 * if button will be clicked the UID will be inserted into the ttContentUid value field
	 *
	 * @param $PA
	 * @param $fObj
	 * @return string
	 */
	public function getContentUid($PA, $fObj) {
		$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
			'uid',
			'tt_content',
			'pid=' . $PA['pid'] . '
			AND sorting < ' . $PA['row']['sorting'] .
			BackendUtility::BEenableFields('tt_content') .
			BackendUtility::deleteClause('tt_content'),
			'', 'sorting DESC', ''
		);

		$label = LocalizationUtility::translate('getPreviousContentUid', 'Rateeverything');
		$output = '<div style="margin-top: 8px; margin-left: 4px;">';
		$onClick = "
			document." . $PA['formName'] . "['" . $PA['itemName'] . "'].value=" . $row['uid'] . ";
			typo3form.fieldGet('data[tt_content][" . $PA['row']['uid'] . "][pi_flexform][data][sDEFAULT][lDEF][settings.ttContentUid][vDEF]','int','',1,'0');
			TBE_EDITOR.fieldChanged('tt_content','" . $PA['row']['uid'] . "','pi_flexform','data[tt_content][" . $PA['row']['uid'] . "][pi_flexform]');
			return false;
		";
		$output .= '<a href="#" onclick="' . htmlspecialchars($onClick) . '" style="padding: 3px; border: 1px solid black; background-color: #999">' . $label . '</a>';
		$output .= '</div>';
		return $output;
	}

}
<?php
namespace JWeiland\Rateeverything\Configuration;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @package rateeverything
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ExtConf implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * allowed tables
	 *
	 * @var string
	 */
	protected $allowedTables;

	/**
	 * allow multiple ratings
	 *
	 * @var boolean
	 */
	protected $allowMultipleRatings;

	/**
	 * round result
	 *
	 * @var integer
	 */
	protected $roundResult;





	/**
	 * constructor of this class
	 * This method reads the global configuration and calls the setter methods
	 */
	public function __construct() {
		// get global configuration
		$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['rateeverything']);
		if (is_array($extConf) && count($extConf)) {
			// call setter method foreach configuration entry
			foreach($extConf as $key => $value) {
				$methodName = 'set' . ucfirst($key);
				if (method_exists($this, $methodName)) {
					$this->$methodName($value);
				}
			}
		}
	}

	/**
	 * getter for allowed tables
	 *
	 * @throws \Exception
	 * @return array
	 */
	public function getAllowedTables() {
		if (empty($this->allowedTables)) {
			throw new \Exception('There are no allowed tables set in extension configuration');
		} else {
			return GeneralUtility::trimExplode(',', $this->allowedTables);
		}
	}

	/**
	 * setter for allowed tables
	 *
	 * @param string $allowedTables
	 * @return void
	 */
	public function setAllowedTables($allowedTables) {
		$this->allowedTables = (string) $allowedTables;
	}

	/**
	 * getter for allow multiple ratings
	 *
	 * @return boolean
	 */
	public function getAllowMultipleRatings() {
		return $this->allowMultipleRatings;
	}

	/**
	 * setter for allow multiple ratings
	 *
	 * @param boolean $allowMultipleRatings
	 * @return void
	 */
	public function setAllowMultipleRatings($allowMultipleRatings) {
		$this->allowMultipleRatings = (bool) $allowMultipleRatings;
	}

	/**
	 * getter for round result
	 *
	 * @return integer
	 */
	public function getRoundResult() {
		if (empty($this->roundResult)) {
			return 0;
		} else return $this->roundResult;
	}

	/**
	 * setter for round result
	 *
	 * @param integer $roundResult
	 * @return void
	 */
	public function setRoundResult($roundResult) {
		$this->roundResult = (integer) $roundResult;
	}

}
<?php
namespace JWeiland\Rateeverything\Domain\Model;

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
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @package rateeverything
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Rating extends AbstractEntity {

	/**
	 * Amount
	 *
	 * @var integer
	 */
	protected $amount = 0;

	/**
	 * Rating
	 *
	 * @var float
	 */
	protected $rating = 0;

	/**
	 * Parent
	 *
	 * @var integer
	 */
	protected $parent = 0;

	/**
	 * Tablename
	 *
	 * @var string
	 */
	protected $tablename = '';





	/**
	 * Returns the amount
	 *
	 * @return integer $amount
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Sets the amount
	 *
	 * @param integer $amount
	 * @return void
	 */
	public function setAmount($amount) {
		$this->amount = (int) $amount;
	}

	/**
	 * Returns the rating
	 *
	 * @return float $rating
	 */
	public function getRating() {
		return $this->rating;
	}

	/**
	 * Sets the rating
	 *
	 * @param float $rating
	 * @return void
	 */
	public function setRating($rating) {
		$this->rating = (float) $rating;
	}

	/**
	 * Returns the parent
	 *
	 * @return integer $parent
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * Sets the parent
	 *
	 * @param integer $parent
	 * @return void
	 */
	public function setParent($parent) {
		$this->parent = (int) $parent;
	}

	/**
	 * Returns the tablename
	 *
	 * @return string $tablename
	 */
	public function getTablename() {
		return $this->tablename;
	}

	/**
	 * Sets the tablename
	 *
	 * @param string $tablename
	 * @return void
	 */
	public function setTablename($tablename) {
		$this->tablename = $tablename;
	}

}
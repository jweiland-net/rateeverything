<?php
namespace JWeiland\Rateeverything\Domain\Repository;

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
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @package rateeverything
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RatingRepository extends Repository {

	/**
	 * get rating for table and given parent UID
	 *
	 * @param $table
	 * @param $parent
	 * @return \JWeiland\Rateeverything\Domain\Model\Rating
	 */
	public function getRating($table, $parent) {
		$query = $this->createQuery();

		$constraint = array();
		$constraint[] = $query->equals('tablename', $table);
		$constraint[] = $query->equals('parent', $parent);

		return $query->matching($query->logicalAnd($constraint))->execute()->getFirst();
	}

}
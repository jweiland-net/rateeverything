<?php
namespace JWeiland\Rateeverything\Utility;

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
use JWeiland\Rateeverything\Domain\Model\Rating;

/**
 * @package rateeverything
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class StarsUtility {

	/**
	 * create an array for displaying the stars for rating value
	 *
	 * 0.00 - 1.25 = 1.0 Star
	 * 1.25 - 1.75 = 1.5 Stars
	 * 1.75 - 2.25 = 2.0 Stars
	 * 2.25 - 2.75 = 2.5 Stars
	 * 2.75 - 3.25 = 3.0 Stars
	 * 3.25 - 3.75 = 3.5 Stars
	 * 3.75 - 4.25 = 4.0 Stars
	 * 4.25 - 4.75 = 4.5 Stars
	 * 4.75 - 5.00 = 5.0 Stars
	 *
	 * @param \JWeiland\Rateeverything\Domain\Model\Rating $rating
	 * @return array Returns CSS-Classes for each star
	 */
	public function getArrayForStars(Rating $rating) {
		$value = $rating->getRating();
		$stars = array();

		for ($i = 0.75; $i <= 5.00; $i++) {
			if ($value > $i) {
				$stars[(int)ceil($i)] = 'icon-star-2';
			} else {
				if ($value > ($i - 0.5)) {
					$stars[(int)ceil($i)] = 'icon-star-3';
				} else {
					$stars[(int)ceil($i)] = 'icon-star';
				}
			}
		}
		return $stars;
	}

}
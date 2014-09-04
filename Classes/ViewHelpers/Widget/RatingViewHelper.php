<?php
namespace JWeiland\Rateeverything\ViewHelpers\Widget;

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

/**
 * @package rateeverything
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RatingViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper {

	/**
	 * @var \JWeiland\Rateeverything\ViewHelpers\Widget\Controller\RatingController
	 */
	protected $controller;

	/**
	 * inject rating controller
	 *
	 * @param Controller\RatingController $ratingController
	 * @return void
	 */
	public function injectRatingController(\JWeiland\Rateeverything\ViewHelpers\Widget\Controller\RatingController $ratingController) {
		$this->controller = $ratingController;
	}

	/**
	 * render VH widget
	 *
	 * @param \JWeiland\Rateeverything\Domain\Model\Rating $rating
	 * @param integer $contentUid
	 * @return string
	 */
	public function render($rating = NULL, $contentUid) {
		return $this->initiateSubRequest();
	}
}
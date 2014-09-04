<?php
namespace JWeiland\Rateeverything\ViewHelpers\Widget\Controller;

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
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;

/**
 * @package rateeverything
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RatingController extends AbstractWidgetController {

	/**
	 * @var \JWeiland\Rateeverything\Utility\StarsUtility
	 */
	protected $starsUtility;

	/**
	 * inject stars utility
	 *
	 * @param \JWeiland\Rateeverything\Utility\StarsUtility $starsUtility
	 * @return void
	 */
	public function injectStarsUtility(\JWeiland\Rateeverything\Utility\StarsUtility $starsUtility) {
		$this->starsUtility = $starsUtility;
	}

	/**
	 * first action which will be executed
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('contentUid', (int)$this->widgetConfiguration['contentUid']);
		if (is_array($this->settings['svgStars']) && !empty($this->settings['svgStars']['outerRadius'])) {
			$this->view->assign('dimension', (int)$this->settings['svgStars']['outerRadius'] * 2 + 2);
		} else {
			$this->view->assign('dimension', 0);
		}
		if ($this->widgetConfiguration['rating'] instanceof Rating) {
			$this->view->assign('stars', $this->starsUtility->getArrayForStars($this->widgetConfiguration['rating']));
		} else {
			$stars = array();
			for ($i = 1; $i <= 5; $i++) {
				$stars[$i] = 'icon-star';
			}
			$this->view->assign('stars', $stars);
		}
	}

}
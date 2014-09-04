<?php
namespace JWeiland\Rateeverything\Ajax;

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
class SaveRating extends AbstractAjaxRequest {

	/**
	 * @var \JWeiland\Rateeverything\Domain\Repository\RatingRepository
	 */
	protected $ratingRepository;

	/**
	 * @var \JWeiland\Rateeverything\Configuration\ExtConf
	 */
	protected $extConf;

	/**
	 * @var \JWeiland\Rateeverything\Utility\StarsUtility
	 */
	protected $starsUtility;

	/**
	 * inject rating repository
	 *
	 * @param \JWeiland\Rateeverything\Domain\Repository\RatingRepository $ratingRepository
	 * @return void
	 */
	public function injectRatingRepository(\JWeiland\Rateeverything\Domain\Repository\RatingRepository $ratingRepository) {
		$this->ratingRepository = $ratingRepository;
	}

	/**
	 * inject extension configuration
	 *
	 * @param \JWeiland\Rateeverything\Configuration\ExtConf $extConf
	 * @return void
	 */
	public function injectExtConf(\JWeiland\Rateeverything\Configuration\ExtConf $extConf) {
		$this->extConf = $extConf;
	}

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
	 * process ajax request
	 *
	 * @param array $arguments Arguments to process
	 * @return string
	 */
	public function processAjaxRequest(array $arguments) {
		// cast arguments
		list($rating, $table, $parent) = $this->castArguments($arguments);

		if (empty($rating) || empty($table) || empty($parent)) {
			return json_encode(array('empty variables'));
		}

		// validate arguments
		if (!$this->validateArguments(serialize(array($table, $parent)), $arguments['hash'])) {
			return json_encode(array('invalid hash'));
		}

		// check if user has rated this element already
		if (!$this->extConf->getAllowMultipleRatings()) {
			$sessionKey = 'rate-' . $table . '-' . $parent;
			$sessionValue = $GLOBALS['TSFE']->fe_user->getKey('ses', $sessionKey);
			if (empty($sessionValue)) {
				$GLOBALS['TSFE']->fe_user->setKey('ses', $sessionKey, TRUE);
			} else return json_encode(array('element already rated by current user'));
		}

		$record = $this->ratingRepository->getRating($table, $parent);
		if ($record instanceof Rating) {
			$newRating = round(($record->getRating() * $record->getAmount() + $rating) / ($record->getAmount() + 1), $this->extConf->getRoundResult());
			$record->setRating($newRating);
			$record->setAmount($record->getAmount() + 1);
		} else {
			$newRating = $rating;
			/** @var \JWeiland\Rateeverything\Domain\Model\Rating $record */
			$record = $this->objectManager->get('JWeiland\\Rateeverything\\Domain\\Model\\Rating');
			$record->setRating($rating);
			$record->setAmount(1);
			$record->setTablename($table);
			$record->setParent($parent);
		}

		$this->ratingRepository->add($record);

		$result = array();
		$result['stars'] = $this->starsUtility->getArrayForStars($record);
		$result['rating'] = $newRating;

		return json_encode($result, JSON_FORCE_OBJECT);
	}

	/**
	 * cast arguments
	 *
	 * @param array $arguments
	 * @return array
	 */
	protected function castArguments(array $arguments) {
		$castedArguments = array();
		$castedArguments[] = (float) $arguments['rating'];
		$castedArguments[] = (string) htmlspecialchars($arguments['table']);
		$castedArguments[] = (int) $arguments['parent'];
		return $castedArguments;
	}

}
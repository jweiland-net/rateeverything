<?php
namespace JWeiland\Rateeverything\Controller;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * @package rateeverything
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class RatingController extends ActionController {

	/**
	 * @var \JWeiland\Rateeverything\Domain\Repository\RatingRepository
	 */
	protected $ratingRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * @var \JWeiland\Rateeverything\Utility\SvgUtility
	 */
	protected $svgUtility;

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
	 * inject hash service
	 *
	 * @param \TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService
	 * @return void
	 */
	public function injectHashService(\TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService) {
		$this->hashService = $hashService;
	}

	/**
	 * inject svg utility
	 *
	 * @param \JWeiland\Rateeverything\Utility\SvgUtility $svgUtility
	 * @return void
	 */
	public function injectSvgUtility(\JWeiland\Rateeverything\Utility\SvgUtility $svgUtility) {
		$this->svgUtility = $svgUtility;
	}

	/**
	 * initialize action
	 *
	 * @return void
	 */
	public function initializeAction() {
		if ($this->settings['createSvgStars']) {
			$this->svgUtility->createSvgStars($this->settings['svgStars']);
		}
	}

	/**
	 * action show
	 *
	 * @return void
	 */
	public function showAction() {
		$this->view->assign('siteUrl', GeneralUtility::getIndpEnv('TYPO3_SITE_URL'));
		$this->view->assign('siteId', $GLOBALS['TSFE']->id);
		$this->view->assign('rateParent', $this->resolveParent());
		$this->view->assign('contentUid', $this->configurationManager->getContentObject()->data['uid']);
		$this->view->assign('hash', $this->hashService->generateHmac(
			serialize(array($this->settings['table'], $this->resolveParent()))
		));

		$rating = $this->ratingRepository->getRating($this->settings['table'], $this->resolveParent());
		if ($rating instanceof Rating) {
			$this->view->assign('rating', $rating);
		} else $this->view->assign('rating', $this->objectManager->get('JWeiland\\Rateeverything\\Domain\\Model\\Rating'));
	}

	/**
	 * resolve parent UID from flexform configuration
	 *
	 * @return integer The UID of the configured record
	 */
	protected function resolveParent() {
		switch ($this->settings['table']) {
			case 'pages':
				$uid = (int)$GLOBALS['TSFE']->id;
				break;
			case 'tt_content':
				$uid = (int)$this->settings['ttContentUid'];
				break;
			default:
				$parts = GeneralUtility::trimExplode('[', $this->settings['getParam']);
				$get = GeneralUtility::_GET();
				foreach ($parts as $part) {
					$part = rtrim($part, ']');
					$get = $get[$part];
				}
				$uid = (int)$get;
		}
		return $uid;
	}

}
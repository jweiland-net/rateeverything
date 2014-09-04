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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @package rateeverything
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SvgUtility {

	/**
	 * @var \TYPO3\CMS\Fluid\View\StandaloneView
	 */
	protected $view = NULL;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $objectManager = NULL;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager = NULL;

	protected $starTypes = array('normal', 'half', 'full');

	/**
	 * inject object manager
	 * we need it to get standaloneview of fluid
	 *
	 * @param ObjectManager $objectManager
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManager $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * inject configuration manager
	 * We need the integrated contentObject, to fill constructor of standaloneview
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 */
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	 * initializes the standalone view of fluid
	 *
	 * @return void
	 */
	public function initializeObject() {
		// StandaloneView view needs contentObject.
		// if we don't set it, StandaloneView will override ConfigurationManager with an empty cObj in singleton-cache
		$this->view = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView', $this->configurationManager->getContentObject());
	}

	/**
	 * generates the dynamic stars and saves them as SVG file in uploads/tx_rateeverything
	 *
	 * @param array $settings
	 * @return void
	 */
	public function createSvgStars(array $settings) {
		// initialize view
		$this->view->setFormat('svg');
		$this->view->setTemplatePathAndFilename(
			ExtensionManagementUtility::extPath('rateeverything') . 'Resources/Private/Templates/Star.svg'
		);

		// cast settings to integer
		foreach ($settings as $key => $setting) {
			if (MathUtility::canBeInterpretedAsInteger($setting)) {
				$settings[$key] = (int)$setting;
			}
		}

		// set some variables which are valid for all types of stars
		$this->view->assign('settings', $settings);
		$this->view->assign('coordinates', $this->getCoordinatesForStar($settings));
		$this->view->assign('widthAndHeightBackground', 2 + ($settings['outerRadius'] * 2));

		// loop over our three star types and generate 3 SVG images
		foreach ($this->starTypes as $starType) {
			$path = PATH_site . 'uploads/tx_rateeverything/star-' . $starType . '.svg';
			if (!@file_exists($path) || $settings['override']) {
				$this->view->assign('starType', $starType);
				file_put_contents($path, $this->view->render());
			}
		}
	}

	/**
	 * this methods generates the points of our star and
	 * renders them into a SVG readable string for coordinates
	 *
	 * @param array $settings
	 * @return string
	 */
	protected function getCoordinatesForStar(array $settings) {
		$points = $this->getPoints($settings);
		$coordinates = array();
		foreach ($points as $point) {
			// add inner point
			$coordinates[] = implode(',', $point);
		}
		return implode(' ', $coordinates);
	}

	/**
	 * get points of star
	 *
	 * @param array $settings
	 * @return array points
	 */
	protected function getPoints(array $settings) {
		// get outer and inner points for our star
		$points = array();
		$angel = 360 / $settings['spikes'];
		for ($i = 0; $i < $settings['spikes']; $i++) {
			// add inner point
			$point = array(
				'x' => $settings['paddingLeft'] + $settings['outerRadius'] + round(sin(deg2rad($i * $angel + ($angel / 2))) * $settings['innerRadius'], 2),
				'y' => $settings['paddingTop'] + $settings['outerRadius'] - round(cos(deg2rad($i * $angel + ($angel / 2))) * $settings['innerRadius'], 2),
			);
			$points[] = $point;

			// add outer point
			$point = array(
				'x' => $settings['paddingLeft'] + $settings['outerRadius'] + round(sin(deg2rad(($i + 1) * $angel)) * $settings['outerRadius'], 2),
				'y' => $settings['paddingTop'] + $settings['outerRadius'] - round(cos(deg2rad(($i + 1) * $angel)) * $settings['outerRadius'], 2),
			);
			$points[] = $point;
		}
		return $points;
	}

}
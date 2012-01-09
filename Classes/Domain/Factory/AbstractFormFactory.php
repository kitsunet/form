<?php
namespace TYPO3\Form\Domain\Factory;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Form".                 *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * This
 * **This class is meant to be subclassed by developers.**
 * @api
 */
abstract class AbstractFormFactory implements FormFactoryInterface {

	/**
	 * The settings of the TYPO3.Form package
	 *
	 * @var array
	 * @api
	 */
	protected $settings;

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Configuration\ConfigurationManager
	 * @internal
	 */
	protected $configurationManager;

	/**
	 * @internal
	 */
	public function initializeObject() {
		$this->settings = $this->configurationManager->getConfiguration(\TYPO3\FLOW3\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Form');
	}

	/**
	 *
	 * @param string $presetName
	 * @return array
	 * @api
	 */
	protected function getPresetConfiguration($presetName) {
		if (!isset($this->settings['Presets'][$presetName])) {
			throw new \TYPO3\Form\Exception\PresetNotFoundException(sprintf('The Preset "%s" was not found underneath TYPO3: Form: Presets.', $presetName), 1325685498);
		}
		$preset = $this->settings['Presets'][$presetName];
		if (isset($preset['parentPreset'])) {
			$parentPreset = $this->getPresetConfiguration($preset['parentPreset']);
			unset($preset['parentPreset']);
			$preset = \TYPO3\FLOW3\Utility\Arrays::arrayMergeRecursiveOverrule($parentPreset, $preset);
		}
		return $preset;
	}
}
?>
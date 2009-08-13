<?php
/***************************************************************
 *  Copyright notice
 *
 * (c) 2009 Oliver Hader <oliver@typo3.org>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Common class for extension zend_framework.
 *
 * @author	Oliver Hader <oliver@typo3.org>
 * @package	TYPO3
 */
class tx_zendframework {
	const EXTKEY = 'zend_framework';
	const EXTCONF_AddIncludePath = 'addIncludePath';
	const EXTCONF_RegisterAutoload = 'registerAutoload';

	/**
	 * Contains the extension configuration accordant to the
	 * settings in extension manager.
	 *
	 * @var	array
	 */
	private static $extensionConfiguration = array();

	/**
	 * Handles classes that should be autoloaded and start with 'Zend_'.
	 *
	 * @param	string		$className: The name of the class to be loaded
	 * @return	void
	 */
	public static function autoload($className) {
		if (strpos($className, 'Zend_') === 0) {
			$filePath = PATH_tx_zendframework . str_replace('_', '/', $className) . '.php';
			require $filePath;
		}
	}

	/**
	 * Initializes the zend_framework extension.
	 *
	 * @return	void
	 */
	public static function initialize() {
		self::$extensionConfiguration = unserialize(
			$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][self::EXTKEY]
		);

		if (self::$extensionConfiguration) {
			self::initializeAutoloader();
			self::initializeIncludePaths();
		}
	}

	/**
	 * Initializes the autoloader accordant to the setting in extension manager.
	 *
	 * @return	void
	 */
	public static function initializeAutoloader() {
		if (self::isEnabled(self::EXTCONF_RegisterAutoload)) {
			spl_autoload_register('tx_zendframework::autoload');
		}
	}

	/**
	 * Initializes the setting of include paths accordant to the setting extension manager.
	 *
	 * @return	void
	 */
	public static function initializeIncludePaths() {
		if (self::isEnabled(self::EXTCONF_AddIncludePath)) {
			$includePath = get_include_path();

			if (strpos($includePath, PATH_tx_zendframework) === false) {
				set_include_path(
					PATH_tx_zendframework . (!empty($includePath) ? PATH_SEPARATOR : '') . $includePath
				);
			}
		}
	}

	/**
	 * Determines whether a extension configuration key is enabled.
	 * 
	 * @param	string		$configurationKey: The key to be checked
	 * @return	boolean		Whether the extension configuration key is enabled
	 */
	private static function isEnabled($configurationKey) {
		return (
			isset(self::$extensionConfiguration[$configurationKey])
			&& self::$extensionConfiguration[$configurationKey]
		);
	}
}
?>
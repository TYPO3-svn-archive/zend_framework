<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

define('PATH_tx_zendframework', t3lib_extMgm::extPath('zend_framework'));

require_once PATH_tx_zendframework . 'class.tx_zendframework.php';
tx_zendframework::initialize();
?>
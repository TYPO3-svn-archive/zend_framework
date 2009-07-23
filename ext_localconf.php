<?php

if (!defined ('TYPO3_MODE')) die ('Access denied.');

/**
 * Fuege den Zend Framework Pfad dem PHP Includepfad hinzu.
 * 
 * Auf einigen Servern besteht ein vorinstalliertes Zend Framework, manchmal
 * auch in einer veralteten Version.
 * Um sicher zu gehen, dass Versionskonflikte entstehen, fuegen wir den
 * Zend Framework Pfad direkt nach dem Punkt (.) hinzu.
 */
$separator = (DIRECTORY_SEPARATOR == '\\') ? ';' : ':' ;
$includePath = ini_get('include_path');
$includePaths = split($separator, $includePath);

if(!empty($includePaths) && ($includePaths[0] == '.')) {
	array_shift($includePaths);
	array_unshift($includePaths, t3lib_extMgm::extPath('zend_framework'));
	array_unshift($includePaths, '.');		
} else {
	array_unshift($includePaths, t3lib_extMgm::extPath('zend_framework'));		
}
ini_set('include_path', implode($separator, $includePaths));


/**
 * Registriere eine eindeutige Autoload Callback Funktion.
 * 
 * Der Autoloader erlaubt ein Instanzieren von Objekten ohne vorheriges
 * Inkludieren und wird u.a. auch in der Zend_Loader Komponente genutzt.
 */
spl_autoload_register('zend_framework_autoload');

function zend_framework_autoload($class) {
	if(substr($class, 0, 5) == 'Zend_') {
		$path = t3lib_extMgm::extPath('zend_framework') . str_replace('_', '/', $class) . '.php';
		require_once($path);
	}
}

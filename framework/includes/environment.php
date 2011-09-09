<?php

/**
 * Jeder Fehler wird ausgegeben sowie Vorschläge für bestmögliche
 * Interoperabilität und zukünftige Kompatibilität des Codes.
 **/
error_reporting(E_ALL | E_STRICT);

/**
 * Prüft, ob magic_quotes_gpc = On und löscht sie gegebenenfalls aus den
 * übergebenen Variablen
 **/
if (!get_magic_quotes_gpc())
{
    $_GET = array_map('stripslashes', $_GET);
    $_POST = array_map('stripslashes', $_POST);
    $_COOKIE = array_map('stripslashes', $_COOKIE);
}

define('DS', DIRECTORY_SEPARATOR);
// absoluter Root-Pfad zum Arbeitsverzeichnis
define('WRK_ROOT', realpath(dirname(__FILE__).DS.'..'.DS.'..').DS);
// absoluter Root-Pfad zum Applikations-Verzeichnis
define('APP_ROOT', WRK_ROOT.'app'.DS);
// absoluter Root-Pfad zum Framework-Verzeichnis
define('FRM_ROOT', WRK_ROOT.'framework'.DS);

// In dieses Verzeichnis werden die Sessions gespeichert:
ini_set('session.save_path', FRM_ROOT.'tmp'.DS.'sessions');

// additional include files
// Nicht benötigte Bibliotheken können ausgeklammert werden (Session,...)
$aif = array(
	'path'  	=> WRK_ROOT.'config'.DS.'path.php',
	'database'	=> WRK_ROOT.'config'.DS.'database.php',
	'routes'	=> WRK_ROOT.'config'.DS.'routes.php',

	'basics'	=> FRM_ROOT.'includes'.DS.'basics.php',

	'model'		=> FRM_ROOT.'libs'.DS.'models'.DS.'model.php',
//	'session'	=> FRM_ROOT.'libs'.DS.'session.php',
//	'auth'		=> FRM_ROOT.'libs'.DS.'auth.php'
	);

// alle im Array aif definierten Dateien werden eingebunden
foreach ($aif as $v)
{
	if (file_exists($v)) require_once($v);
}

// Extrahiert alle Routen anhand der in der routes.php gemachten Angaben als
// Variablen. Bsp: :action wird zu $_action
extract(get_routes($map_connect), EXTR_PREFIX_ALL, '');


// additional load path
$alp = array(
	'controllers'	=> APP_ROOT.'controllers'.DS,
	'models'		=> APP_ROOT.'models'.DS,
	'views'			=> APP_ROOT.'views'.DS,
	);

$files = array();
$files[] = $alp['models'].$_controller.'.php';
$files[] = $alp['controllers'].$_controller.'.php';

foreach ($files as $v)
{
	if (file_exists($v)) require_once($v);
}

?>

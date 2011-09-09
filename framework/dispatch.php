<?php
require_once('includes/environment.php');

// Wenn die aufgerufene Funktion existiert, dann wird sie aufgerufen.
// Wenn nicht, dann wird 404 zurückgegeben
if (class_exists($_controller) && function_exists($_action))
{
	// Verbindung zum Controller. Variablenname ist er Controllername!
	${$_controller} = new $_controller($db);

    // Aufruf der Funktion
	$array = $_action($_id);
}
else
{
	header('Status: 404 Not Found');
	exit('<h2>Page not found</h2>The requested page was not found');
}

$params['layout']	= $_controller;
$params['action']	= $_action;
$params['path']		= PATH;

// $array['user']['name']      = $auth->session->get('username');

// debug: Das Array vor der Umwandlung
// print_r($array);

// Das Array wir in ein String gewandelt.
$str = array_to_xml($array);
// Umwandlung in UTF-8, da Umlaute sonst Probleme bereiten
$str = utf8_encode($str);

// unser Hauptcontent
$xml = new DomDocument('1.0', 'UTF-8');
$xml->loadXML($str);

// debug: Das XML-Dokument
// $xml->formatOutput = TRUE;
// print($xml->saveXML());

// das XSL-Stylesheet
$xsl = new DomDocument('1.0', 'UTF-8');

// für produktive Umgebung @ nicht vergessen
if (!$xsl->load(APP_ROOT.'views/'.$_controller.'.xsl'))
{
	trigger_error('Could not load XSL-Template!', E_USER_ERROR);
}

$xpr = new XsltProcessor();
$xsl = $xpr->importStylesheet($xsl);

foreach ($params AS $i => $v)
{
	$xpr->setParameter('', $i, $v);
}

$xhtml = $xpr->transformToXML($xml);


header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: text/html; charset=utf-8');

print $xhtml;
?>


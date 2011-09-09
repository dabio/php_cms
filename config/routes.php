<?php
/**
 * Hier kann man eigene Routen spezifizieren.
 * Bsp: dev/:controller/:action/:id
 * Das letzte Zeichen darf kein '/' sein!
 * Durch ein '*' am Ende wird alles nachfolgende der Variablen zugeordnet.
 **/
// Die erste Route: zu den Seiteninhalten
$map_connect[]	= array(
	'/:id*',
	'controller'=> 'cms',
	'action'	=> 'index',
	'id'		=> ''
);

// Die zweite Route: zum Admin-Panel
$map_connect[]	= array(
	'/4600da0df39030a225fbc4098d48f004/:controller/:action/:id',
	'controller'=> 'content',
	'action'	=> 'index',
	'id'		=> NULL
);
?>

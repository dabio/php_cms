<?php

/**
 * index - Wird ausgeführt, wenn kein 'action' übergeben wird.
 *
 * @return void
 * @author Danilo Braband
 **/
function index($page = FALSE)
{
	$cms = $GLOBALS['cms'];

    // letztes '/' löschen, falls vorhanden.
    $page = preg_replace('/\/$/', '', $page);

	// Inhalte aus der Datenbank laden
	$arr = $cms->find_by_url($page);

    if (empty($arr))
    {
    	header('Status: 404 Not Found');
    	exit('<h2>Page not found</h2>The requested page was not found');
    }

	foreach ($arr AS $i => $v)
	{
		if (!function_exists($v['markup']))
		{
			// Einbinden der Markup-Funktion
			require_once(FRM_ROOT.'libs'.DS.'markup'.DS.$v['markup'].'.php');
		}

		// Markup-Funktion ausführen und Resultat in Format einbetten
		$arr[$i]['content'] = '<![CDATA['.$v['markup']($v['content']).']]>';
	}

	$arr['tree'] = $cms->tree();

	// den Pfad zum Element finden -> des letzten Elements
	$arr['path2node'] = $cms->find_path2node($v['lft'], $v['rgt']);

	return $arr;
}


?>

<?php

/**
 * array_to_xml - Konvertiert ein Array in einen sauberen XML String.
 * zurueck.
 *
 * @return string
 * @author Danilo Braband
 **/
function array_to_xml($array, $level = 1)
{
    $xml = '';

    $level == 1 ? $xml .= '<?xml version="1.0" encoding="UTF-8"?>'.
                          '<root>' : $xml .= '';

    if (is_array($array)) {

        foreach($array as $i => $v) {

            $xml .= is_numeric($i) ? '<item>' : '<'.$i.'>';

            if (is_array($v)) {
                $level++;
                $xml .= array_to_xml($v, $level);
                $level--;
            } else {
                $xml .= $v;
            }

            $xml .= is_numeric($i) ? '</item>' : '</'.$i.'>';
        }

    }

    $level == 1 ? $xml .= "</root>" : $xml .= '';

    return $xml;
}



/**
 * get_routes - Anhand der uebergebenden URI und der MAP wird ein array mit
 * dem Controller und der Action zurückgegeben
 *
 * @param string
 * @return array
 * @author Danilo Braband
 **/
function get_routes($map)
{
	// die aufgerufene URL, ersten und letzten Slash entfernen
	$url = preg_replace('/^\/|\/$/', '', $_SERVER['REQUEST_URI']);

	// Zählvariablen mit Standardwerten belegen
	$count_match    = -1;	    // Anzahl der Übereinstimmungen.
	$route_num      = 0;	    // Nummer der Route.
	$route_name     = array();	// Die Route.

    $url = explode('/', $url);

    foreach ($map AS $i => $v)
    {
        if (!isset($v[0])) {
            trigger_error('Wrong URL definition. Conrtol your routes.php!');
        }

        // die definierte Route, ersten und letzten Slash entfernen
        $route = preg_replace('/^\/|\/$/', '', $v[0]);
        $route = explode('/', $route);

        $matches = 0;
        $new_route = array();

        foreach ($route AS $ri => $rv)
        {
            if (!isset($url[$ri]))
            {
                continue;
            }

            if ($url[$ri] == $route[$ri])
            {
                $matches++;
            }

        }

		if ($matches > $count_match)
		{
			$count_match= $matches;
			$route_num  = $i;
			$route_name = $route;
		}
    }

    $new_map = array();
    foreach ($route_name AS $i => $v)
    {
        if (isset($url[$i])
            and $url[$i] != $v
            and substr($v, 0, 1) == ':')
        {
            $name = substr($v, strrpos($v, ':') + 1);

            // Wenn das letzte Zeichen ein '*' ist, wird die gesamte Rest-URL
            // der neuen Map zugeordnet.
            if (substr($name, -1) == '*')
            {
                $name = substr($name, 0, -1);
                $map_el = count($url)-$i;

                for ($n = 0; $n < $i; $n++)
                { 
                    array_shift($url);
                }
                $new_map[$name] = implode('/', $url);
            }
            else
            {
                $new_map[$name] = $url[$i];
            }
        }
    }

	foreach ($map[$route_num] AS $i => $v)
	{
		if (!array_key_exists($i, $new_map)
			or empty($new_map[$i]))
		{
			$new_map[$i] = $v;
		}
	}

	return $new_map;
}

?>

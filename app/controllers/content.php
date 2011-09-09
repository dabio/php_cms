<?php

/**
 * index - Wird ausgeführt, wenn kein 'action' übergeben wird.
 *
 * @return array
 * @author Danilo Braband
 **/
function index ()
{
    $content = $GLOBALS['content'];

    $array['tree'] = $content->tree();

	return $array;
}



/**
 * add_page - Fügt eine Seite dazu.
 *
 * @param parent - Das Elternelement der neuen Seite.
 * @return array
 * @author Danilo Braband
 **/
function add ($parent = false)
{
    $content = $GLOBALS['content'];

	$array['edit'][0]['parent'] = $parent;
	$array['markup'] = $content->find_markup();

	// Muss für den Ajax-Request geändert werden!
	$GLOBALS['_controller'] = 'ajax_content';

	return $array;
}



/**
 * edit - Sucht nach einem Datensatz anhand des ID als Parameter.
 *
 * @param int
 * @return array
 * @author Danilo Braband
 **/
function edit ($id = false)
{
    $content = $GLOBALS['content'];
	
	$array['edit'] = $content->find_all('id = '.$id);
	$array['markup'] = $content->find_markup();

	// Muss für den Ajax-Request geändert werden!
	$GLOBALS['_controller'] = 'ajax_content';

	return $array;
}



/**
 * preview - Vorschau der Seite.
 *
 * @return void
 * @author Danilo Braband
 **/
function preview ()
{
    $content = $GLOBALS['content'];

    if (empty($_POST))
    {
        return false;
    }

    $my_array = $content->clean($_POST);

    $mark = $content->find_markup($my_array['f_markup']);

	if (!function_exists($mark[0]['name']))
	{
		// Einbinden der Markup-Funktion
		require_once(FRM_ROOT.'libs'.DS.'markup'.DS.$mark[0]['name'].'.php');
	}

    foreach ($my_array AS $i => $v)
    {
        $my_array[$i] = utf8_decode($my_array[$i]);
        $my_array[$i] = stripslashes($my_array[$i]);
    }

	// Markup-Funktion ausführen und Resultat in Format einbetten
    if (isset($my_array['content'])
        and isset($mark[0]['name']))
    {
	    $my_array['content'] = '<![CDATA['.$mark[0]['name']($my_array['content']).']]>';
	    
    }

    $array['preview'] = $my_array;

	// Muss für den Ajax-Request geändert werden!
	$GLOBALS['_controller'] = 'ajax_content';

	return $array;
}



/**
 * save_page - Übernimmt die POST-Variable und speichert den Eintrag in der
 * Datenbank.
 *
 * @param int
 * @return array
 * @author Danilo Braband
 **/
function save ()
{
	if (!isset($_POST) and !isset($_POST['submit']))
	{
		return false;
	}

    $content = $GLOBALS['content'];

    switch ($_POST['submit'])
    {
        case 'save':

            $r = $content->save($_POST);

            break;

        case 'delete':
            
            $r = $content->del($_POST);

            break;

        default:
    }

	header('Location: '.PATH.'4600da0df39030a225fbc4098d48f004/content/');
	exit;
	
	return true;
}



/**
 * tree - Liest die Baumstruktur neu aus.
 *
 * @return void
 * @author Danilo Braband
 **/
function tree ()
{
    $content = $GLOBALS['content'];

	$array['tree'] = $content->tree();

	// Muss für den Ajax-Request geändert werden!
	$GLOBALS['_controller'] = 'ajax_content';

	return $array;
}


/*****************************************************************************
 *
 * PRIVATE FUNCTIONS (should be)
 *
 ****************************************************************************/



?>

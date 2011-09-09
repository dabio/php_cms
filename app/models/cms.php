<?php

class Cms extends Model
{
	public function __construct(&$db)
	{
		$this->tablename = 'cms_page';

		// Muss aufgerufen werden, sonst wird der Kontruktor der Elternklasse
		// nicht ausgeführt.
		parent::__construct($db);
	}



	/**
	 * Sucht nach dem angegebenen Namen. Nutzt dazu die Methode find_all() des
	 * Elternelements.
	 *
	 * @return array
	 * @author Danilo Braband
	 **/
	public function find_by_url ($where = false)
	{
		// Aufbau des Sucharrays
		$sql	= array(
			'select' =>	'p.*, '.
						'm.name AS markup',
			'from' =>	'cms_page AS p'.
						' LEFT JOIN cms_markup AS m ON p.f_markup = m.id',
			// Vergleich des übergebenden Strings mit dem Namen des Datenbank-
			// eintrages.
			'where' =>	'p.url = "'.$where.'"'
			);

		// Aufruf des Elternelements und Rückgabe des Rückgabewertes.
		return parent::find_all($sql);
	}



	/**
	 * Baut einen Suchstring auf, der alle Elemente abhängig vom Elternelement
	 * finden soll. Siehe dazu:
	 * http://www.sitepoint.com/print/hierarchical-data-database
	 *
	 * @return array
	 * @author Danilo Braband
	 **/
	public function find_childs ($lft = false, $rgt = false, $parent = false)
	{
		if (isset($parent))
		{
			$where = ' OR (parent = '.$parent.')';
		}
		else
		{
			$where = '';
		}
		
		$sql = array(
			'select' =>	'url, title',
			'where' =>	'(lft BETWEEN '.$lft.' AND '.$rgt.
						' AND parent IS NOT NULL)'.
						$where,
			'orderby'=>	'lft ASC'
			);

		// Aufruf des Elternelements und Rückgabe des Rückgabewertes.
		return parent::find_all($sql);
	}



	/**
	 * Damit bekommt man den Pfad zu einem bestimmten Blatt/Child
	 *
	 * @return array
	 * @author Danilo Braband
	 **/
	public function find_path2node ($lft = false, $rgt = false)
	{
		$sql = array(
			'select' =>	'url, title, parent',
			'where' =>	'lft < '.$lft.' AND rgt > '.$rgt,
			'orderby'=>	'lft ASC'
			);

		// Aufruf des Elternelements und Rückgabe des Rückgabewertes.
		return parent::find_all($sql);
	}



	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Danilo Braband
	 **/
	public function tree ()
	{
		// Zuerst wird das Root-Element ausgelesen um den linken und rechten
		// Wert zu bekommen.
		$r = parent::find_all(array(
			'select' =>	'lft, rgt',
			'orderby'=>	'lft ASC',
			'limit'  =>	'1'
			)); 

		// Jetzt alle Child-Elemente auslesen. Inklusive Root-Element.
		$array = parent::find_all(array(
			'select' =>	'id, title, parent, url',
			'where'	 =>	'lft BETWEEN '.$r[0]['lft'].' AND '.$r[0]['rgt'],
			'orderby'=> 'lft ASC'
			));

		return $array;
	}
}

?>

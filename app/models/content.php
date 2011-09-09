<?php

class Content extends Model
{
	public function __construct(&$db)
	{
		$this->tablename = 'cms_page';

		// Muss aufgerufen werden, sonst wird der Kontruktor der Elternklasse
		// nicht ausgeführt.
		parent::__construct($db);
	}



	/**
	 * add - Fügt dem Tree eine neue Seite hinzu.
	 *
	 * @param child - Das Array der einzufügenden Seite.
	 * @param parend_id - Der ID des Elternelements. 
	 * @return bool
	 * @author Danilo Braband
	 **/
	public function add ($_)
	{
		if (!is_array($_))
		{
			return false;
		}

		// links des Elternelements finden:
		if (!$p = $this->find_lft_rgt($_['parent']))
		{
			return false;
		}

		// SQL-Statement zum Update des Baumes:
		$sql =	'UPDATE '.$this->tablename.' SET rgt = rgt + 2'.
				' WHERE rgt > '.$p[0]['lft'];
		if (!$r = $this->db->query($sql))
		{
			return false;
		}
		
		$sql =	'UPDATE '.$this->tablename.' SET lft = lft + 2'.
				' WHERE lft > '.$p[0]['lft'];
        if (!$r = $this->db->query($sql))
        {
        	return false;
        }

		$_['lft'] = $p[0]['lft'] + 1;
		$_['rgt'] = $p[0]['lft'] + 2;

		$result = $this->insert_row($_);

		if (!$result)
		{
			return false;
		}
		
		return true;
	}



    /**
     * clean - Übernimmt ein Array und entfernt überflüssige Elemente und gibt
     * ein valides Feld zurück. Zusätzlich werden Zeichen gefiltert.
     *
     * @param array
     * @return void
     * @author Danilo Braband
     **/
    public function clean ($_ = false)
    {
        if (!is_array($_))
        {
            return false;
        }

    	$fields     = array('id', 'parent', 'lft', 'rgt', 'url', 'title',
    	                    'content', 'f_markup');
        $illegal    = array('&', '<');
        $legal      = array('&amp;', '&lt;');

        $my_array = array();

    	foreach ($_ AS $i => $v)
    	{
    		if (in_array($i, $fields, true))
    		{
    			if (isset($v) and $v != '')
    			{
    			    $my_array[$i] = str_replace($illegal, $legal, $v);
    			}
    		}
    	}

        return $my_array;
    }


	/**
	 * del - Löscht eine Seite in der Datenbank und passt alle restlichen
	 * Seiten dem Baum an.
	 *
	 * @return bool
	 * @author Danilo Braband
	 **/
	public function del ($_ = false)
	{
		if (!is_array($_))
		{
			return false;
		}

        // Woher soll ich wissen, was ich löschen soll, wenn ich keine ID
        // kenne?
        if (!isset($_['id']))
        {
            return false;
        }

		// links und rechts des Elements finden:
		if (!$element = $this->find_lft_rgt($_['id']))
		{
			return false;
		}

        // Prüfung, ob es sich um das Root-Element handelt.
        $left = (int) $element[0]['lft'];
        if ($left == 1)
		{
			return false;
		}

        // Alle Unterelemente löschen, rekursiver Aufbau!
        $leafs = $this->find_all(array(
            'select' => 'id',
            'where' =>  'parent = '.$_['id']
            ));

        // Rekursive Löschung der Elemente:
        foreach ($leafs AS $v)
        {
            $this->del($v);
        }

		// SQL-Statement zum Update des Baumes:
		$sql =	'UPDATE '.$this->tablename.' SET rgt = rgt - 2'.
				' WHERE rgt > '.$element[0]['lft'];
		
		if (!$this->db->query($sql))
		{
			return false;
		}
		
		$sql =	'UPDATE '.$this->tablename.' SET lft = lft - 2'.
				' WHERE lft > '.$element[0]['lft'];

		if (!$this->db->query($sql))
		{
			return false;
		}

        // Seite löschen
 		return $this->delete_row(array('id' => $_['id']));
	}



	/**
	 * find_markup - Such nach Markup-Einträgen in der Datenbank.
	 *
	 * @param id - Wenn ein ID angegeben wurde, dann nur dieser Datensatz. 
	 * @return void
	 * @author Danilo Braband
	 **/
	public function find_markup ($id = false)
	{
		$id = $id ? 'id = '.$id : 1;

		$sql = array(
			'from'	=> 'cms_markup',
			'where'	=> $id
			);

		return $array = parent::find_all($sql);
	}



	/**
	 * find_lft_rgt - Sucht nacht Left der übergebenen Seiten-ID.
	 *
	 * @param id - Der Datensatz-ID.
	 * @return void
	 * @author Danilo Braband
	 **/
	private function find_lft_rgt ($_ = false)
	{
		if (!isset($_))
		{
			return false;
		}

		$sql = array(
			'select'	=> 'lft, rgt',
			'where'		=> 'id = '.$_,
			'limit'     => '1'
			);
		
		return parent::find_all($sql);
	}



    /**
     * find_url - Sucht nach der URL der Übergebenen ID in der Datenbank und
     * gibt ihn bei Erfolg zurück.
     *
     * @return void
     * @author Danilo Braband
     **/
    private function find_url ($_ = false)
    {
        if (!is_string($_))
        {
            return false;
        }

		$sql = array(
			'select'	=> 'url',
			'where'		=> 'id = '.$_
			);

        return parent::find_all($sql);

    }


    /**
     * save - Speichert den Inhalt eines Array in der Datenbank
     *
     * @param array
     * @return bool
     * @author Danilo Braband
     **/
    public function save ($_ = false)
    {
        if (!is_array($_))
        {
            return false;
        }

        // Eliminieren wir erstmal falsche und leere Elemente. Die '0' bleibt
        // erhalten!
        $_ = $this->clean($_);

        // Ein Titel ist natürlich Pflicht.
        if (!isset($_['title']))
        {
            return false;
        }

        // Wo soll ich das einordnen, wenn ich kein Elternelement habe?
        if (!isset($_['parent']))
        {
            return false;
        }

        $clean_title    = $this->urlify($_['title']);
        $parent_url     = $this->find_url($_['parent']);

        // Zusammensetzen der neuen URL:
        if (empty($parent_url[0]['url']))
        {
            // Seite bereits vorhanden?
            if (isset($_['id']))
            {
                $r = $this->find_lft_rgt($_['id']);
                // handelt es sich hier um das Root-Element?
                if ((int) $r[0]['lft']  == 1)
                {
                    $_['url'] = '';
                }
            }
            else
            {
                $_['url'] = $clean_title;
            }
        }
        else
        {
            $_['url'] = $parent_url[0]['url'].'/'.$clean_title;
        }

        // Wenn das Array keine ID hat, wird davon ausgegangen, dass sie noch
        // nicht vorhanden ist. Die Seite wird angelegt und die Funktion wird
        // verlassen.
        if (!isset($_['id']))
        {
            return $this->add($_);
        }

        // Die Seite wird aktualisiert.
        if (!$this->update_row($_, array('id' => (int) $_['id'])))
        {
            return false;
        }

        // Wenn die aktuelle Seite ein Elternelement ist, müssen alle Kinder
        // mit der neuen URL aktualisiert werden.
        $r = $this->find_lft_rgt($_['id']);

        // Wenn rechts gleich links + 1 ist, dann handelt es sich um ein
        // Blatt, d.h. es gibt keine nachfolgenden Elemente zum Aktualisieren.
        if ((int) $r[0]['rgt'] == (int) $r[0]['lft'] + 1)
        {
            return true;
        }

        $lft = (int) $r[0]['lft'] + 1;
        $rgt = (int) $r[0]['rgt'] - 1;

        // Die Kinder müssen aktualisiert werden. Alle Unterelemente auslesen:
		$r = parent::find_all(array(
            'select' => 'parent, id, title',
			'where'	 =>	'lft BETWEEN '.$lft.' AND '.$rgt,
			'orderby'=> 'lft ASC'
			));

        // Rekursive Aktualisierung der Elemente:
        foreach ($r AS $v)
        {
            // Überschriften müssen merkwürdigerweise encodiert werden, da es
            // sonst Schwierigkeiten mit der DB gibt.
            $v['title'] = utf8_encode($v['title']);
            $this->save($v);
        }

        return true;

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
			'select' =>	'id, title, parent',
			'where'	 =>	'lft BETWEEN '.$r[0]['lft'].' AND '.$r[0]['rgt'],
			'orderby'=> 'lft ASC'
			));

		return $array;
	}



    /**
     * urlify - Filtert nicht erlaubte Elemente aus einem String und gibt
     * einen bereinigten String zur Nutzung als URL zurück.
     *
     * @param string
     * @return void
     * @author Danilo Braband
     **/
    function urlify ($_ = false)
    {
        if (!is_string($_))
        {
            return false;
        }

        // alles in Kleinbuchstaben
        $_ = mb_strtolower($_, mb_detect_encoding($_));

        $illegal= array('{', '}', '|', '\\', '^', '~', '[', ']', '`', '&lt;',
                        '>', '#', '%', '"', '&amp;', '/', '?', ':', '@', ';',
                        '=', '&', '<');
        $_ = str_replace($illegal, '', $_);
        $_ = str_replace(' ', '-', $_);

        $_ = str_replace(array('à', 'á', 'â', 'ã', 'ä', 'å', 'æ'), 'a', $_);
        $_ = str_replace('ç', 'c', $_);
        $_ = str_replace(array('è', 'é', 'ê', 'ë'), 'e', $_);
        $_ = str_replace(array('ì', 'í', 'î', 'ï'), 'i', $_);
        $_ = str_replace('ñ', 'n', $_);
        $_ = str_replace(array('ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'œ'), 'o', $_);
        $_ = str_replace(array('ù', 'ú', 'û', 'ü'), 'u', $_);
        $_ = str_replace('ß', 'ss', $_);

        return $_;
    }

};

?>

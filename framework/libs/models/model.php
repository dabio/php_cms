<?php

// die Datenbankengine wird eingebunden
require_once(FRM_ROOT.'libs'.DS.'models'.DS.'dbo_'.$db['engine'].'.php');

class Model
{
	// Die Datenbankverbindungskennung
	protected $db;

	// der Tabellenname
	protected $tablename;

    // Fehlermeldungen
    protected $error;



	public function __construct (&$db)
	{
        $this->error = array();
	
		$this->db = new $db['engine']($db['host'], $db['user'], $db['pass'], $db['db']);

		// debug:
		// var_dump($this->conn);
	}



	public function __destruct ()
	{
	}



	/**
	 * Übernimmt ein Array, setzt daraus das SQL-Statement zusammen und
	 * übergibt das Statement dem DBMS.
	 *
	 * @return void
	 * @author Danilo Braband
	 **/
	public function delete_row ($array = NULL)
	{
		if (!is_array($array))
		{
			return false;
		}

        $sql = 'DELETE FROM '.$this->tablename.' WHERE ';
        foreach ($array as $i => $v)
        {
            $sql .= '`'.$i.'` = "'.$v.'" AND ';
        }
        // letztes ' AND ' entfernen
        $sql = substr($sql, 0, -5);

		// für Umlaute, etc...
		$sql = utf8_decode($sql.';');

        return $result = $this->db->query($sql);;
	}



	public function find_all ($conditions = NULL)
	{
		$sql = $this->select_parse_conditions($conditions);
		
		$result = $this->db->query($sql);

		if ($result->isError())
		{
			trigger_error('Unable to fetch rows!');
			return false;
		}

		$array = array();
		while($row = $result->fetch())
		{
			$array[] = $row;
		}

		return $array;
	}



	/**
	 * Übernimmt ein Array und setzt daraus einen SQL-String zusammen.
	 *
	 * @return string - Das SQL-Statement
	 * @param array
	 * @author Danilo Braband
	 **/
    public function insert_row ($array = NULL)
    {
        // wenn kein Array übergeben wurde, abbrechen
        if (!is_array($array)) return false;

        $sql = 'INSERT INTO '.$this->tablename;

        $sql .= ' SET';
        foreach ($array as $i => $v)
        {
            // Wenn Eintrag der String 'NULL' ist, dann NULL
            if ($v == 'NULL')
            {
                $sql .= ' `'.$i.'` = NULL, ';
            }
            else
            {
                $sql .= ' `'.$i.'` = "'.$v.'", ';
            }
        }
        // letztes ', ' entfernen
        $sql = substr($sql, 0, -2);

		// für Umlaute, etc...
		$sql = utf8_decode($sql.';');

		return $result = $this->db->query($sql);
    }	


	
	/**
	 * Übernimmt ein Array oder ein String und überprüft, setzt daraus einen
	 * SQL-String zusammen.
	 *
	 * @return string - Das SQL-Statement
	 * @param array|string
	 * @author Danilo Braband
	 **/
	private function select_parse_conditions ($param)
	{
		if (is_string($param))
		// Es wurde nur ein String übergeben -> nur WHERE statement wurde
		// angegeben.
		{
			$sql = 'SELECT * FROM '.$this->tablename.' WHERE '.$param;
		}
		else if (is_array($param))
		// Basierend auf die Indexnamen des Arrays wird das SQL-Statement
		// aufgebaut.
		// select, from, where, groupby, having, orderby, limit
		{
			if (isset($param['select']))
			{
				$select = 'SELECT '.$param['select'];
			}
			else
			{
				$select = 'SELECT *';
			}

			if (isset($param['from']))
			{
				$from = ' FROM '.$param['from'];
			}
			else
			{
				$from = ' FROM '.$this->tablename;
			}

			if (isset($param['where']))
			{
				$where = ' WHERE '.$param['where'];
			}
			else
			{
				$where = ' WHERE 1';
			}

			if (isset($param['groupby']))
			{
				$groupby = ' GROUP BY '.$param['groupby'];
			}
			else
			{
				$groupby = '';
			}

			if (isset($param['having']))
			{
				$having = ' HAVING '.$param['having'];
			}
			else
			{
				$having = '';
			}

			if (isset($param['orderby']))
			{
				$orderby = ' ORDER BY '.$param['orderby'];
			}
			else
			{
				$orderby = '';
			}

			if (isset($param['limit']))
			{
				$limit = ' LIMIT '.$param['limit'];
			}
			else
			{
				$limit = '';
			}

			$sql = $select.$from.$where.$groupby.$having.$orderby.$limit.';';
		}
		else
		// Es wurde keine Kondition übergeben, also wird eine Statement für
		// alle Daten aufgebaut.
		{
			$sql = 'SELECT * FROM '.$this->tablename.' WHERE 1';
		}

		// FOR DEBUGGING:
		// print $sql;

		return $sql;
	}



	/**
	 * Übernimmt ein Array und setzt daraus einen SQL-String zusammen. Dieser
	 * String wird dann dem DBMS übergeben und ausgeführt.
	 *
	 * @return void
	 * @author Danilo Braband
	 **/
	public function update_row ($array = NULL, $where = NULL)
	{
        // wenn kein Array übergeben wurde, abbrechen
        if (!is_array($array) or !is_array($where)) return false;

        $sql = 'UPDATE '.$this->tablename;

        $sql .= ' SET';
        foreach ($array as $i => $v)
        {
			$sql .= ' `'.$i.'` = "'.$v.'", ';
        }
        $sql = substr($sql, 0, -2);

		$sql .= ' WHERE ';
		foreach ($where AS $i => $v)
		{
			$sql .= '`'.$i.'` = "'.$v.'" AND ';
		}
        // letztes ', ' entfernen
        $sql = substr($sql, 0, -5);

		// für Umlaute, etc...
		$sql = utf8_decode($sql).';';

        return $this->db->query($sql);
	}

}; // class Model

?>

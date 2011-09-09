<?php

/**
 * MySQL-Klasse zum Verbindungsaufbau
 *
 * @access public
 * @package database
 **/
class MySQL
{
    /**
     * Hostname des MySQL-Servers
	 *
     * @access private
     * @var string
     **/
    private $dbHost;
    
    /**
     * MySQL-Benutzername
	 *
     * @access private
     * @var string
     **/
    private $dbUser;
    
    /**
     * MySQL-Passwort
	 *
     * @access private
     * @var string
     **/
    private $dbPass;
    
    /**
     * Name der ausgewählten Datenbank
	 *
     * @access private
     * @var string
     **/
    private $dbName;
    
    /**
     * MySQL-Verbindungskennung
	 *
     * @access private
     * @var string
     **/
    private $dbConn;

    /**
     * Fehler beim Verbindungsaufbau werden in dieser Variablen gespeichert
     * @access private
     * @var boolean
     **/
    private $connectError;



    /**
     * MySQL-Konstruktor
	 *
     * @param string dbhost (Hostname des MySQL-Servers)
     * @param string dbUser (MySQL-Benutzername)
     * @param string dbPass (MySQL-Passwort)
     * @param string dbName (Name der Datenbank)
     * @access public
     **/
    public function __construct ($dbHost, $dbUser, $dbPass, $dbName)
    {
        $this->dbHost   = $dbHost;
        $this->dbUser   = $dbUser;
        $this->dbPass   = $dbPass;
        $this->dbName   = $dbName;

        $this->connectDB();
    } // __construct()



    /**
     * Verbindet zu MySQL und wählt eine Datenbank aus
	 *
     * @return void
     * @access private
     **/
    private function connectDB ()
    {
        // Baut die Verbindung zum MySQL-Server auf.
        if (!$this->dbConn = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass))
        {
            trigger_error('Could not connect to server!', E_USER_ERROR);
            $this->connectError = TRUE;
        }
        else if (!@mysql_select_db($this->dbName, $this->dbConn))
        {
            trigger_error('Could not select database!', E_USER_ERROR);
            $this->connectError = TRUE;
        }
    } // connectDB



    /**
     * Fragt MySQL-Fehler ab
	 *
     * @return boolean
     * @access public
     **/
    public function isError ()
    {
        if ($this->connectError) return TRUE;
        
        $error = mysql_error($this->dbConn);
        
        return empty($error) ? FALSE : TRUE;
    }



    /**
     * Gibt eine Instanz von MySQLResult zurück, um Zeilen aus dem Abfrage-
     * ergebnis zu holen.
	 *
     * @param string die auszuführende Datenbankabfrage
     * @return MySQLResult
     * @access public
     **/
    public function query($sql)
    {
        if (!$result = mysql_query($sql, $this->dbConn))
        {
            trigger_error ('Query failed: '.mysql_error($this->dbConn).' SQL: '.$sql, E_USER_ERROR);
        }

        return new MySQLResult($this, $result);
    }


};


/**
 * MySQLResult-Klasse für Abfrageergebnisse
 *
 * @access public
 * @package database
 **/
class MySQLResult
{
    /**
     * Instanz von MySQL, die eine Datenbankverbindung zur Verfügung stellt.
	 *
     * @access private
     * @var MySQL
     **/
    private $instance;

    /**
     * Kennung eines Abfrageergebnisses
	 *
     * @access private
     * @var resource
     **/
    private $query;



    /**
     * MySQLResult-Konstruktor
	 *
     * @param object mysql (Instanz der MySQL-Klasse)
     * @param resource query (MySQL-Ergebniskennung)
     * @access public
     **/
    public function __construct ($instance, $query)
    {
        $this->instance = $instance;
        $this->query    = $query;
    }



    /**
     * Liest eine Zeile des Abfrageergebnisses
	 *
     * @return array
     * @access public
     **/
    public function fetch ()
    {
        if ($row = mysql_fetch_array($this->query, MYSQL_ASSOC))
        {
            return $row;
        }
        else if ($this->size() > 0)
        {
            mysql_data_seek($this->query, 0);
            return false;
        }
        else
        {
            return false;
        }
    }



    /**
     * Gibt die Anzahl der Zeilen in einem Ergebnis zurück
	 *
     * @return int
     * @access public
     **/
    public function size ()
    {
        return mysql_num_rows($this->query);
    }



    /**
     * Gibt den ID des zuletzt eingefügten Records zurück
	 *
     * @return int
     * @access public
     **/
    public function insertID ()
    {
        return mysql_insert_id($this->mysql->dbConn);
    }



    /**
     * Fragt MySQL-Fehler ab
	 *
     * @return boolern
     * @access public
     **/
    public function isError ()
    {
        return $this->instance->isError();
    }


};

?>

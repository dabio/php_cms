<?php
/**
 * Authentication-Klasse<br />
 * Authentifiziert Benutzer automatisch
 * <b>Beachten Sie:</b> Die Klasse Session muss verfügbar sein!
 *
 * @package FRM
 * @access public
 */


class Auth
{
    /**
     * Instanz der Klasse für die Datenbankverbindung
     *
     * @access private
     * @var object
     **/
    private $db;
    
    /**
     * Instanz der Klasse Session
     *
     * @access private
     * @var Session
     **/
    public $session;

    /**
     * URL, auf die weitergeleitet wird, wenn die Authentifizierung fehlschlägt
     *
     * @access private
     * @var string
     **/
    private $redirect;

    /**
     * String, der zum Generieren des Hash aus Benutzername und Passwort
     * benutzt wird
     *
     * @access private
     * @var string
     **/
    private $hashKey;

    /**
     * Wurden Passwörter veschlüsselt?
     *
     * @access private
     * @var boolean
     **/
    private $sha1;



    /**
     * Auth-Konstruktor<br />
     * Prüft automatisch, ob der Benutzer gültig ist
     *
     * @param object Datenbankverbindung
     * @param string URL, auf die bei fehlerhaftem Login weitergeleitet wird
     * @param string Schlüssel, der beim Generieren des Hash aus Benutzername
     *               und Passwort benutzt wird
     * @param boolean falls Passwörter in der Datenbank mit sha1 verschlüsselt
     *                werden (optional))
     * @access public
    **/
    public function __construct($db, $redirect, $hashKey, $sha1 = true)
    {
        $this->db       = $db;
        $this->redirect = $redirect;
        $this->hashKey  = $hashKey;
        $this->sha1     = $sha1;

        $this->session  = new Session;

        $this->login();
    }



    /**
     * Prüft Benutzername und Passwort gegen die Datenbank
     *
     * @return void
     * @access private
    **/
    private function login ()
    {
        // Prüfen, ob bereits Werte in der Session gespeichert sind
        if ($this->session->get('login_hash')
            and !isset($_POST['username']) and !isset($_POST['password']))
        {
            $this->confirmAuth();
            return;
        }

        // $_POST-Variablen prüfen, falls dies eine neue Anmeldung ist
        if (!isset($_POST['username']) or !isset($_POST['password']))
        {
            $_POST['username'] = 'GUEST';
            $_POST['password'] = 'guest';
        }

        if ($this->sha1)
        {
            $password = sha1($_POST['password']);
        }
        else
        {
            $password = $_POST['password'];
        }

        $username = $_POST['username'];

        // Abfrage, um die Anzahl der Benutzer mit dieser Benutzername/Passwort-
        // Kombination zu erfragen
        $sql =  'SELECT count(*) AS num_users'.
                ' FROM wm_user'.
                ' WHERE username = "'.$username.'"'.
                ' AND password = "'.$password.'"';

        $result = $this->db->query($sql);
        $row = $result->fetch();

        // Weiterleiten, falls nicht exakt ein Eintrag zurückgegeben wird
        if ($row['num_users'] != 1)
        {
            $this->redirect();
        }
        else
        // Andernfalls ist dies ein gültiger Benutzer
        {
            $this->storeAuth($username, $password);
        }
    }



    /**
     * Setzt die Sessionvariable nach erfolgreichem Login
     *
     * @return void
     * @access private
    **/
    private function storeAuth ($username, $password)
    {
        $this->session->set('username', $username);
        $this->session->set('password', $password);

        // Eine Sessionvariable zur Bestätigung von Benutzersitzungen
        $hashKey = sha1($this->hashKey.$username.$password);
        $this->session->set('login_hash', $hashKey);

    }



    /**
     * Bestätigt, ob ein bestehender Login noch gültig ist
     *
     * @return void
     * @access private
    **/
    private function confirmAuth ()
    {
        $username = $this->session->get('username');
        $password = $this->session->get('password');
        $hashKey  = $this->session->get('login_hash');
        
        if (sha1($this->hashKey.$username.$password) != $hashKey)
        {
            $this->logout(true);
        }
    }



    /**
     * Meldet den Benutzer ab
     *
     * @param boolean Parameter, der an Auth::redirect übergeben wird (optional)
     * @return void
     * @access public
    **/
    public function logout ($from = false)
    {
        $this->session->del('username');
        $this->session->del('password');
        $this->session->del('login_hash');

        $this->redirect($from);
    }



    /**
     * Leitet den Browser weiter und beendet die Ausführung des Scriptes
     *
     * @param boolean die URL, von der dieser Benutzer kam (optional)
     * @return void
     * @access private
    **/
    private function redirect ($from = true)
    {
        if ($from)
        {
            header('Location: '.$this->redirect.'?from='.$_SERVER['REQUEST_URI']);
        }
        else
        {
            header('Location: '.$this->redirect);
        }

        exit();
    }

};


?>

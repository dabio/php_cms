<?php
/**
 * Ein Wrapper für die Sessionsfunktionen in PHP
 *
 * <code>
 * $session = new Session();
 * $session->set('message', 'Hello World!');
 * print($session->get('message')); // Gibt 'Hello World!' aus
 * </code>
 *
 * @package FRM
 * @access public
 */


class Session
{
    /**
     * Session-Konstruktor<br />
     * Startet die Session mit session_start()
     * <b>Beachten Sie:</b> Wenn die Session bereits gestartet wurde, bleibt
     * session_start() wirkungslos
     *
     * @access public
     **/
    public function __construct()
    {
        session_start();
    }



    /**
     * Setzt die Sessionvariable
     *
     * @param string Name der Variablen
     * @param mixed Wert der Variablen
     * @return void
     * @access public
     **/
    public function set ($name, $value)
    {
        $_SESSION[$name] = $value;
    }



    /**
     * Holt eine Stringvariable
     *
     * @param string Name der Variablen
     * @return mixed Wert der Sessionvariablen
     * @access public
     **/
    public function get ($name)
    {
        if (isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
        else
        {
            return false;
        }
    }



    /**
     * Löscht eine Sessionvariable
     *
     * @param string Name der Variablen
     * @return void
     * @access public
     **/
    public function del ($name)
    {
        unset($_SESSION[$name]);
    }



    /**
     * Zerstört die gesamte Session
     *
     * @return void
     * @access public
     **/
    public function destroy ()
    {
        $_SESSION = array();
        session_destroy();
    }

};


?>

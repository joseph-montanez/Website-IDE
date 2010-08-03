<?php 
class Gorilla3d_Session
{
    protected $_namespace;
    public function __construct($namespace = 'default') {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->_namespace = $namespace;
    }    
    
    public function logout() {
        $_SESSION[$this->_namespace] = array();
    }
    
    public function get($key) {
        if(!isset($_SESSION[$this->_namespace][$key])) {
            return null;
        }
        return $_SESSION[$this->_namespace][$key];
    }
    
    public function set($key, $value) {
        return $_SESSION[$this->_namespace][$key] = $value;
    }
}

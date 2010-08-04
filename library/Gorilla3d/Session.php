<?php 
class Gorilla3d_Session
{
    protected $namespace;
    public function __construct($namespace = 'default') 
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->namespace = $namespace;
    }    
    
    public function logout() 
    {
        $_SESSION[$this->namespace] = array();
    }
    
    public function get($key) 
    {
        if (!isset($_SESSION[$this->namespace][$key])) {
            return null;
        }
        return $_SESSION[$this->namespace][$key];
    }
    
    public function set($key, $value) 
    {
        return $_SESSION[$this->namespace][$key] = $value;
    }
}

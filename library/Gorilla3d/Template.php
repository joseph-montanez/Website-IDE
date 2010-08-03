<?php

class Gorilla3d_Template
{
    public static $vars;
    public static function load($tmpl, array $variables = array()) {
        foreach($variables as $key => $variable) {
            $$key = self::escape($variable);
            ${'__' . $key} = $variable;
        }
        include('templates/default/' . $tmpl);
    }
    
    public static function escape($value) {
        $value = str_replace(array(
            'javascript:'
        ), '', $value);
        /*
        $value = str_replace(';', "&#59;", $value); 
        $value = str_replace('\\', "&#92;", $value); 
        $value = str_replace('/', "&#47;", $value); 
        $value = str_replace('=', "&#61;", $value);
        */
        
        $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
        
        return $value;
    }
    
    public static function getStatic($name) {
        return implode("\n", (array) self::$vars[$name]);
    }
    
    public static function start($name) {
        if(!is_array(self::$vars)) {
            self::$vars = array();
        }
        if(!isset(self::$vars[$name])) {
            self::$vars[$name] = array();
        }
        ob_start();
    }
    
    public static function end($name) {
        self::$vars[$name] []= ob_get_contents(); 
        ob_end_clean();
    }
}

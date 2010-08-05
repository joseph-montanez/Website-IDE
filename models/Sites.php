<?php
class Sites
{
    public static $tableName = 'sites';
    public $id;
    public $accountId;
    public $username;
    public $passwd;
    public $host;
    public $port;
    public static $crypt;
    
    public function __construct() 
    {
    }
    
    public function setPasswd($passwd) 
    {
        $this->passwd = self::encodePassword($passwd);
        return $this;
    }
    
    public function setUsername($username) 
    {
        $this->username = $username;
        return $this;
    }
    
    public function setHost($host) 
    {
        $this->host = $host;
        return $this;
    }
    
    public function setPort($port) 
    {
        $this->port = $port;
        return $this;
    }
    
    public function setAccountId($accountId) 
    {
        $this->accountId = $accountId;
        return $this;
    }
    
    public function lastInsertId()
    {
        global $db;
        return $db->lastInsertId();
    }
    
    public static function isTable()
    {
        if (!self::exists()) {
            self::createTable();
        }
    }
    
    public static function exists() 
    {
        global $db;
        $sql = 'SELECT name FROM sqlite_master WHERE name = :name';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', self::$tableName, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false) {
            return true;
        }
        return false;
    }
    
    public static function createTable($db = null) 
    {
        global $db;
        $sql = '
            CREATE TABLE IF NOT EXISTS ' . self::$tableName . ' (
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                host TEXT NULL,
                username TEXT NULL,
                passwd TEXT NULL,
                port INTEGER NULL,
                accountId INTEGER NULL
            );
        ';
        return $db->exec($sql);
    }
    
    public static function byId($id) 
    {
        global $db;
        self::isTable();
        $sql = 'SELECT * FROM ' . self::$tableName . ' WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            ':id' => $id
        ));
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Sites');
        $row = $stmt->fetch(PDO::FETCH_CLASS);
        return $row;
    }
    
    public static function fetchAll() 
    {
        global $db;
        self::isTable();
        
        $sql = 'SELECT * FROM ' . self::$tableName;
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_CLASS, 'Sites');
        return (array) $rows;
    }
    
    public static function insert(Sites $site, $db = null) 
    {
        global $db;
        self::isTable();
        $stmt = $db->prepare(
            'INSERT INTO ' . self::$tableName 
            . ' (host, port, username, passwd, accountId) '
            . ' VALUES(:host, :port, :username, :passwd, :accountId)'
        );
        $stmt->execute(
            array(
                ':host' => $site->host,
                ':port' => $site->port,
                ':username' => $site->username,
                ':passwd' => $site->passwd,
                ':accountId' => $site->accountId
            )
        );
    }
    
    public static function getCrypt() 
    {
        if (!self::$crypt) {
            $crypt = mcrypt_module_open('tripledes', '', 'ecb', '');

            $random_seed = strstr(PHP_OS, "WIN") ? MCRYPT_RAND : MCRYPT_DEV_RANDOM;
            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($crypt), $random_seed);

            $expected_key_size = mcrypt_enc_get_key_size($crypt);
            $key = substr(md5(EDITOR_BLOWFISH), 0, $expected_key_size);

            mcrypt_generic_init($crypt, $key, $iv);
            
            self::$crypt = $crypt;
        } 
        
        return self::$crypt;
    }
    
    public static function encodePassword($value) 
    {
        $crypt = self::getCrypt();
        $encoded = mcrypt_generic($crypt, $value);
        $encoded = base64_encode($encoded);
        return $encoded;
    }
    
    public static function decodePassword($value) 
    {
        $crypt = self::getCrypt();
        $decrypted = base64_decode($value);
        $decrypted = mdecrypt_generic($crypt, $decrypted);
        
        return $decrypted;
    }
}
?>

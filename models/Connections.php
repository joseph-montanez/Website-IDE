<?php
class Connections 
{
    public static $tableName = 'connections';
    public $id;
    public $accountId;
    public $username;
    public $passwd;
    public $host;
    public $port;
    public static $db;
    public static $crypt;
    
    public function __construct($db = null) 
    {
        self::setDatabase($db);
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
    
    public static function setDatabase($db = null) 
    {
        if ($db !== null) {
            self::$db = $db;
        }
    }
    
    public static function exists() 
    {
        $sql = 'SELECT name FROM sqlite_master WHERE name = :name';
        $stmt = self::$db->prepare($sql);
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
        self::setDatabase($db);
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
        return self::$db->exec($sql);
    }
    
    public static function byId($id) 
    {
        $sql = 'SELECT * FROM ' . self::$tableName . ' WHERE id = ?';
        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':name', self::$tableName, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_CLASS, 'Connections');
        return $row;
    }
    
    public static function insert(Connections $connection, $db = null) 
    {
        self::setDatabase($db);
        $stmt = self::$db->prepare('INSERT INTO ' . self::$tableName . ' (host, port, username, passwd) VALUES(:host, :port, :username, :passwd)');
        $stmt->bindParam(':host', $connection->host, PDO::PARAM_STR);
        $stmt->bindParam(':post', $connection->port, PDO::PARAM_INT);
        $stmt->bindParam(':username', $connection->username, PDO::PARAM_STR);
        $stmt->bindParam(':passwd', $connection->passwd, PDO::PARAM_STR);
        $stmt->execute();
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

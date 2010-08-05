<?php
class Accounts
{
    public static $tableName = 'accounts';
    public $id;
    public $username;
    public $passwd;
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
    
    public static function exists() 
    {
        global $db;
        $sql = 'SELECT name FROM sqlite_master WHERE name = :name';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(
            ':name', 
            self::$tableName, 
            PDO::PARAM_STR, 
            strlen(self::$tableName)
        );
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
                username TEXT NULL,
                passwd TEXT NULL,
                registerDate INTEGER NULL,
                lastLoginDate INTEGER NULL
            );
        ';
        return $db->exec($sql);
    }
    
    public static function byId($id) 
    {
        global $db;
        $sql = 'SELECT * FROM ' . self::$tableName . ' WHERE id = ?';
        $stmt = $db->query($sql);
        $account = false;
        foreach ($dbh->query($sql) as $row) {
            $account = $stmt->fetchALL(PDO::FETCH_CLASS, get_class(self));
        }
        return $account;
    }
    
    public static function byLogin($username, $password) 
    {  
        global $db;
        $password = self::encodePassword($password);
        $sql = 'SELECT * FROM ' . self::$tableName 
            . ' WHERE username = :username AND passwd = :passwd';
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                ':username' => $username,
                ':passwd' => $password,
            )
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Accounts');
        $account = $stmt->fetch(PDO::FETCH_CLASS);
        $stmt->closeCursor();
        return $account;
    }
    
    public static function insert(accounts $account) 
    {
        global $db;
        $stmt = $db->prepare(
            'INSERT INTO ' . self::$tableName 
            . ' (username, passwd) VALUES(:username, :passwd)'
        );
        $stmt->bindParam(':username', $account->username, PDO::PARAM_STR);
        $stmt->bindParam(':passwd', $account->passwd, PDO::PARAM_STR);
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

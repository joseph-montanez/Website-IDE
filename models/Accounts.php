<?php
class Accounts {
    public static $tableName = 'accounts';
    public $id;
    public $username;
    public $passwd;
    public static $db;
    public static $crypt;
    
    public function __construct($db = null) {
        self::setDatabase($db);
    }
    
    public function setPasswd($passwd) {
        $this->passwd = self::encodePassword($passwd);
        return $this;
    }
    
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }
    
    public static function setDatabase($db = null) {
        if($db !== null) {
            self::$db = $db;
        }
    }
    
    public static function exists() {
        $sql = 'SELECT name FROM sqlite_master WHERE name = :name';
        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':name', self::$tableName, PDO::PARAM_STR, strlen(self::$tableName));
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row !== false) {
            return true;
        }
        return false;
    }
    
    public static function createTable($db = null) {
        self::setDatabase($db);
        $sql = '
            CREATE TABLE IF NOT EXISTS ' . self::$tableName . ' (
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                username TEXT NULL,
                passwd TEXT NULL,
                registerDate INTEGER NULL,
                lastLoginDate INTEGER NULL
            );
        ';
        return self::$db->exec($sql);
    }
    
    public static function byId($id) {
        $sql = 'SELECT * FROM ' . self::$tableName . ' WHERE id = ?';
        $stmt = self::$db->query($sql);
        $account = false;
        foreach ($dbh->query($sql) as $row) {
            $account = $stmt->fetchALL(PDO::FETCH_CLASS, get_class(self));
        }
        return $account;
    }
    
    public static function byLogin($username, $password) {  
        $password = self::encodePassword($password);
        $sql = 'SELECT * FROM ' . self::$tableName . ' WHERE username = :username AND passwd = :passwd';
        $stmt = self::$db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_INT);
        $stmt->bindParam(':passwd', $password, PDO::PARAM_STR, strlen($password));
        $stmt->execute();
        $account = false;
        $accounts = $stmt->fetchALL(PDO::FETCH_CLASS, 'Accounts');
        if(!empty($accounts)) {
            $account = $accounts[0];
        }
        return $account;
    }
    
    public static function insert(accounts $account, $db = null) {
        self::setDatabase($db);
        $stmt = self::$db->prepare('INSERT INTO ' . self::$tableName . ' (username, passwd) VALUES(:username, :passwd)');
        $stmt->bindParam(':username', $account->username, PDO::PARAM_STR);
        $stmt->bindParam(':passwd', $account->passwd, PDO::PARAM_STR);
        $stmt->execute();
    }
    
    public static function getCrypt() {
        if(!self::$crypt) {
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
    
    public static function encodePassword($value) {
        $crypt = self::getCrypt();
        $encoded = mcrypt_generic($crypt, $value);
        
        return $encoded;
    }
    
    public static function decodePassword($value) {
        $crypt = self::getCrypt();
        $decrypted = mdecrypt_generic($crypt, $value);
        
        return $decrypted;
    }
}
?>

<?php
class Gorilla3d_Ssh
{
    public $connection;
    public $connected;
    
    public function __construct($host, $username, $password, $port = 22) {
        // TODO: Do I really need to suppress warnings? :( gots to be a way!
        $this->connection = false;
        $this->connected = false;
        
        // Check for valid host
        $validHost = @fsockopen($host, $port, $errno, $errstr, $timeout = 2);
        
        if ($validHost) {
            fclose($validHost); 
            $this->connection = ssh2_connect($host, $port);
        }
        if ($this->connection) {
            $this->connected = @ssh2_auth_password(
                $this->connection, $username, $password
            );
        }
    }
    
    /**
     *
     * @return bool
     */
    public function isConnected() 
    {
        return $this->connected;
    }
    
    public function command($cmd) 
    {
        $stream = ssh2_exec($this->connection, $cmd);
        stream_set_blocking($stream, true);
        $data = stream_get_contents($stream);
        //fclose($stream);
        return $data;
    }
    
    /**
     *
     * @param $path The remote path to scan
     * @return array
     */
    public function scandir($path) 
    {
        $stream = ssh2_exec($this->connection, 'ls -l ' . escapeshellcmd($path));
        stream_set_blocking($stream, true);
        $data = stream_get_contents($stream);
        fclose($stream);
        $lines = explode("\n", $data);
        array_pop($lines);
        $files = array();
        foreach ($lines as $line) {
            $parts = explode(' ', $line);
            $file  = new Gorilla3d_Ssh_Inode();
            $file->filename = $parts[count($parts) - 1];
            $file->type = strstr('d', $parts[0]) ? 
                Gorilla3d_Ssh_Inode::$DIRECTORY : 
                Gorilla3d_Ssh_Inode::$FILE;
            $file->modDate  = $parts[count($parts) - 3] 
                . ' ' . $parts[count($parts) - 2];
            $files []= $file;
        }
        
        return $files;
    }
}
?>

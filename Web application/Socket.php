<?php
define("CONNECTED", true);
define("DISCONNECTED", false);

/**
 * Socket class
 *
 */
Class Socket
{
    private static $instance;

    private $connection = null;
    
    private $connectionState = DISCONNECTED;
    
    private $defaultHost = "127.0.0.1";
    
    private $defaultPort = 13149;
    
    private $defaultTimeout = 10;
    
    public  $debug = false;
    
    function __construct()
    {
        
    }

    /**
     * Singleton pattern. Returns the same instance to all callers
     *
     * @return Socket
     */
    public static function singleton()
    {
        if (self::$instance == null || ! self::$instance instanceof Socket)
        {
            self::$instance = new Socket();
           
        }
        return self::$instance;
    }

    /**
     * Connects to the socket with the given address and port
     * 
     * @return void
     */
    public function connect($serverHost=false, $serverPort=false, $timeOut=false)
    {        
        if($serverHost == false)
        {
            $serverHost = $this->defaultHost;
        }
        
        if($serverPort == false)
        {
            $serverPort = $this->defaultPort;
        }
        $this->defaultHost = $serverHost;
        $this->defaultPort = $serverPort;
        
        if($timeOut == false)
        {
            $timeOut = $this->defaultTimeout;
        }
        $this->connection = socket_create(AF_INET,SOCK_STREAM,SOL_TCP); 
        
        if(socket_connect($this->connection,$serverHost,$serverPort) == false)
        {
            $errorString = socket_strerror(socket_last_error($this->connection));
            $this->_throwError("Connecting to {$serverHost}:{$serverPort} failed.<br>Reason: {$errorString}");
        }else{
            $this->_throwMsg("Socket connected!");
        }
        
        $this->connectionState = CONNECTED;
    }

    /**
     * Disconnects from the server
     * 
     * @return True on succes, false if the connection was already closed
     */
    public function disconnect()
    {
        if($this->validateConnection())
        {
            socket_close($this->connection);
            $this->connectionState = DISCONNECTED;
            $this->_throwMsg("Socket disconnected!");
            return true;
        }
        return false;
    }

    /**
     * Sends a command to the server
     * 
     * @return string Server response
     */
    public function sendRequest($command)
    {
        if($this->validateConnection())
        {
            $command = $this->encodeMsg($command);
            $result = socket_write($this->connection,$command, strlen($command));
            return $result;
        }
        $this->_throwError("Sending command \"{$command}\" failed.<br>Reason: Not connected");
    }
    
    public function isConn()
    {
        return $this->connectionState;
    }
    
    public function getUnreadBytes()
    {
        
        $info = socket_get_status($this->connection);
        return $info['unread_bytes'];

    }
    
    public function getConnName(&$addr, &$port)
    {
        if ($this->validateConnection())
        {
            socket_getsockname($this->connection,$addr,$port);
        }
    }
    
    /**
     * Gets the server response (not multilined)
     * 
     * @return string Server response
     */
    public function getResponse()
    {
        $sRecvBuf = '';
        while(true){
            $sBuf = socket_read($this->connection,4096);
            if(strlen($sBuf) != 0){
                $sRecvBuf .= $sBuf;
            }else{
                break;
            }
        }
        $sRecvBuf = str_replace("]", "", $sRecvBuf);
        return $this->decodeMsg($sRecvBuf);
    }

    public function encodeMsg($sendBuf)
    {
        $str = '';
        $len = strlen($sendBuf);
        for($i=0;$i<$len;$i++) {
            $str .= chr(0) . $sendBuf[$i];
        }
        return $str;
    }

    public function decodeMsg($recvBuf)
    {
        $len = strlen($recvBuf);
        $str = '';
        for($i=0;$i<$len;$i++) {
            if(ord($recvBuf[$i]) >= 128){
                $byte = ord($recvBuf[$i]) - 256;
            }else{
                $byte = ord($recvBuf[$i]);
            }
            if($byte > 0){
                $str .= chr($byte);
            }
        }
        return $str;
    }

    public function waitForResponse()
    {
        if($this->validateConnection())
        {
            return socket_read($this->connection, 2048);
        }
        
        $this->_throwError("Receiving response from server failed.<br>Reason: Not connected");
        return false;
    }

    /**
     * Validates the connection state
     * 
     * @return bool
     */
    private function validateConnection()
    {
        return (is_resource($this->connection) && ($this->connectionState != DISCONNECTED));
    }

    /**
     * Throws an error
     * 
     * @return void
     */
    private function _throwError($errorMessage)
    {
        echo "Socket error: " . $errorMessage;
    }

    /**
     * Throws an message
     * 
     * @return void
     */
    private function _throwMsg($msg)
    {
        if ($this->debug)
        {
            echo "Socket message: " . $msg . "\n\n";
        }
    }

    /**
     * If there still was a connection alive, disconnect it
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}

?>
<?php

// activate full error reporting
//error_reporting(E_ALL & E_STRICT);

include 'XMPPHP/XMPP.php';

class messenger {
    var $server = "";
    var $port = "5222";
    var $resource = "";
    var $user = "";
    var	$pass = "";
    var	$domain = "";
    var	$configured = false;

    function __construct($msgconfig) {
        // Basic check
        if (count($msgconfig) < 4) { return; }
        if (!isset($msgconfig['user'])) { return; }
        if (!isset($msgconfig['pass'])) { return; }
        if (!isset($msgconfig['domain'])) { return; }

        // Set things
        $this->server = (isset($msgconfig['server'])) ? $msgconfig['server'] : "127.0.0.1" ;
        $this->resource = (isset($msgconfig['resource'])) ? $msgconfig['resource'] : "dragonsend" ;
        $this->user = $msgconfig['user'];
        $this->pass = $msgconfig['pass'];
        $this->domain = $msgconfig['domain'];
        $this->configured = true;
    }

    /*
    * function send
    * $destination : string: user@domain
    * $message : string: message to send. HTML compatible 
    */
    function send($destination, $message="Null message set") {
        if ($this->configured == false) { return "Messenger NOT configured, mate"; }

        #Use XMPPHP_Log::LEVEL_VERBOSE to get more logging for error reports
        #If this doesn't work, are you running 64-bit PHP with < 5.2.6?
        
        $conn = new XMPPHP_XMPP($this->server, $this->port, $this->user, $this->pass, $this->resource, $this->domain, $printlog=false, $loglevel=XMPPHP_Log::LEVEL_INFO);
        try {
            $xmppReport = str_replace("\n","<br />\n",str_replace("\r\n","\n", $message));
            $conn->connect();
            $conn->processUntil('session_start');
            $conn->presence();
            $conn->message($destination, $xmppReport);
            $conn->disconnect();
            return;
        } catch(XMPPHP_Exception $e) {
            return $e->getMessage();
        }
    }
}

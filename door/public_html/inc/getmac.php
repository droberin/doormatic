<?php

function getMACforIP($IP) {
        // for permissive tests
        //return "bc:f5:ac:f8:72:fa";

        global $arpbin;
        global $awkbin;
        $MAC = false;
        if ( (is_null($arpbin)) || (is_null($awkbin)) ) { echo "Variable(s) '$arpbin' and/or '$awkbin' not set."; return false; }

        $MACRtrTries = 1;
        while (($MAC == false) && ($MACRtrTries <= 4)) {
                // Lets get that MAC!!
                $handle = popen("$arpbin -a $IP | $awkbin '{print $4}' 2>&1", 'r');
                //echo "'$handle'; " . gettype($handle) . "\n";
                $read = fread($handle, 1000);
                pclose($handle);
                $MAC = chop($read);
                if(!preg_match('/^[0-9a-fA-F]{2}(?=([:;.]?))(?:\\1[0-9a-fA-F]{2}){5}$/', $MAC)) {
                        $MAC = false;
                }
                $MACRtrTries++;
        }
        return $MAC;
}
 
//echo getMACforIP("192.168.1.10");

<?php 
date_default_timezone_set('America/El_Salvador');

function query_time_server ($timeserver, $socket){
    $fp = fsockopen($timeserver,$socket,$err,$errstr,5);
    # parameters: server, socket, error code, error text, timeout
    if($fp)
    {
        fputs($fp, "\n");
        $timevalue = fread($fp, 49);
        fclose($fp); # close the connection
    }
    else
    {
        $timevalue = " ";
    }

    $ret = array();
    $ret[] = $timevalue;
    $ret[] = $err;     # error code
    $ret[] = $errstr;  # error text
    return($ret);
}

function getCurrentTime($format = "H:i:s"){
    $timeserver = "ntp.pads.ufrj.br";
    $timercvd = query_time_server($timeserver, 37);
    if(!$timercvd[1]) {
        $timevalue = bin2hex($timercvd[0]);
        $timevalue = abs(HexDec('7fffffff') - HexDec($timevalue) - HexDec('7fffffff'));
        $tmestamp = $timevalue - 2208988800; # convert to UNIX epoch time stamp
        return date($format, $tmestamp);
    }
    return null;
}

function getCurrentDate($format = "Y/m/d"){
    $timeserver = "ntp.pads.ufrj.br";
    $timercvd = query_time_server($timeserver, 37);
    if(!$timercvd[1]) {
        $timevalue = bin2hex($timercvd[0]);
        $timevalue = abs(HexDec('7fffffff') - HexDec($timevalue) - HexDec('7fffffff'));
        $tmestamp = $timevalue - 2208988800; # convert to UNIX epoch time stamp
        return date($format, $tmestamp);
    }
    return null;
}

$clock = getCurrentTime();
$fdate = getCurrentDate();
?>
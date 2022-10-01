<?php

function pre($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

function pr($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function plog($data){
    $logFile = fopen("stripe_log.txt", "a") or die("Unable to open file!");
    fwrite($logFile, date('d-m-Y H:i:s')." ==> ".$data."\n\n\n");
    fclose($logFile);
}
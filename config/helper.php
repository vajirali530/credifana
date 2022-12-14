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

function getTotalClicks($plan){
    if($plan == 'basic'){
        return 15;
    }else if($plan == 'standard'){
        return 250;
    }else if($plan == 'premium'){
        return 999999;
    }
}

function getPlanName($price){
    if($price == env("STANDARD_PLAN_ID")){
        return 'standard';
    }else if($price == env("PREMIUM_PLAN_ID")){
        return 'premium';
    }
}
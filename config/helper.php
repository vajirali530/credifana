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

function getTotalClicks($price){
    if($price == 'price_1Lni0eEviaLTUto6XV32XGd0'){
        // Basic
        return 15;
    }else if($price == 'price_1Lni1TEviaLTUto6O4tgAZcX'){
        // Standard
        return 250;
    }else if($price == 'price_1Lni1vEviaLTUto6DsnimYJU'){
        // Premium
        return 999999;
    }
}

function getPlanName($price){
    if($price == 'price_1Lni0eEviaLTUto6XV32XGd0'){
        return 'Basic Plan';
    }else if($price == 'price_1Lni1TEviaLTUto6O4tgAZcX'){
        return 'Standard Plan';
    }else if($price == 'price_1Lni1vEviaLTUto6DsnimYJU'){
        return 'Premium Plan';
    }
}
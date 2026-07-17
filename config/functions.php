<?php

function clean($data){

    return htmlspecialchars(trim($data));

}

function redirect($page){

    header("Location: ".$page);
    exit();

}

function currency($amount){

    return "RWF ".number_format($amount);

}

?>
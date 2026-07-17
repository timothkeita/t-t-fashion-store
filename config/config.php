<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

define("SITE_NAME","T & T Fashion Store");
define("SITE_URL","http://localhost/T-T-Fashion-Store");

date_default_timezone_set("Africa/Kigali");

?>
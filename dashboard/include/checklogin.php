<?php
error_reporting(E_ALL & ~E_NOTICE);
include("include/session.php");

if($session->logged_in){
    $authorized = true;
} else {
    $_SESSION["myreferer"] = $_SERVER['HTTP_REFERER'];
    if (!isset($_SESSION["myreferer"])){
        $_SESSION["myreferer"] = $_SERVER['REQUEST_URI'];
    }
    header("Location: login.php");
}

?>
<?php
//date_default_timezone_set('UTC');
date_default_timezone_set('Africa/Algiers');
setlocale(LC_MONETARY, 'dz_DZA');
session_start();
ini_set("display_errors", 1);

// Connect to FireWorks firmware
require_once('../inc/fw.php');
//require_once('../inc/include.php');


//$return = (object) array();
$return = new stdClass;
$return->r = 'error';

if (isset($_GET['logout'])){
    if ($fw->logout())
    {
        $return->r = 'ok';
    }

}else if (isset($_GET['login'])){
    if ( isset($_GET["u"]) && ($_GET["u"] != "") && isset($_GET["p"]) && ($_GET["p"] != "") ){
        if ($fw->login( $_GET["u"], $_GET["p"] ) ){
            $return->r = 'ok';
        }
    }

}else if ($fw->islogin()){
    if (isset($_GET['user'])){
        
        if (isset($_GET['list'])){
            $return = $fw->fetchAll("SELECT * FROM $fw->tb_user WHERE 1");
        
        }else if (isset($_GET['me'])){
            $return = $_SESSION['user'];
        }
    
    }else if (isset($_GET['prod'])){
        
        if (isset($_GET['list'])){
            $return = $fw->fetchAll("SELECT * FROM $fw->tb_user WHERE 1");
        
        }else if (isset($_GET['add'])){
            $return = "not yet";
        }
    }

}

//$fw->log("Url: ".$_SERVER['REQUEST_URI']);
//unset($_GET);
echo json_encode( $return , JSON_NUMERIC_CHECK );
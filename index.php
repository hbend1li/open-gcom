<?php
// Load library and session
require_once('inc/include.php');


//$fw->log("Url: ".$_SERVER['REQUEST_URI']);

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//  LOGOUT
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if (isset($_GET["logout"])){
    if (isset($_SESSION['name']))
        logging( "SIGNOUT ".$_SESSION['name'] ) ;
    $_SESSION = array();
    unset($_SESSION["signin"]);

    session_destroy();
    header("Location: ./");
    exit;
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//  LOGIN signin
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if (
    //isset($_POST["signin"]) &&
    isset($_POST["username"]) &&
    isset($_POST["password"]) &&
    ($_POST["username"] != "") &&
    ($_POST["password"] != "")
)
{
    $username = htmlentities();
    $password = htmlentities($_POST["password"]);

    if (!$fw->login( $_POST["username"], $_POST["password"] ) ){
        $err="Acces Refuser";
    }
    unset($_POST);
}

if (!isset($_SESSION["user"]))
{
    require_once('template/login.html');
    exit;
}




/*
 *

if ((!isset($_SESSION['id_act']))||($_SESSION['id_act']<1)) {
    header("Location: bin/select_activity.php");            //reload.php");
    exit;
}
*/


$fw->fetchAll("UPDATE user SET user.date_login=current_timestamp WHERE id=$_SESSION[signin]");


//$_SESSION['iD']      = 1;

// _____________________________________________________

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cosider Construction QSE</title>

    <link rel="icon" href="img/icon.ico" />

    <link type="text/css" rel="stylesheet" media="all" href="css/font-awesome.css" />
    <link type="text/css" rel="stylesheet" media="all" href="css/default.css">

    <!-- angular ver 1.5.8 --> 
    <script src="js/angular.min.js"></script>
    <script src="js/angular-route.min.js"></script>
    <script src="js/angular-animate.min.js"></script>
    <script src="js/angular-sanitize.js"></script>

    <!--script src="js/markdown.min.js"></script-->


    <script src="js/app.js"></script>
    <!--script src='js/select.js'></script-->

    <!-- JQuery -->
    <script type="text/javascript" src="js/jquery.js"></script>

    <!-- Menu -->
    <script type="text/javascript" src="js/menu.js"></script>
    <link rel="stylesheet" type="text/css" href="css/menu.css">

    <!-- Chat on line -->
    <!--script src="js/chat.js"></script>
    <link type="text/css" rel="stylesheet" media="all" href="css/chat.css" /-->


    <!--link href="https://fonts.googleapis.com/css?family=Maven+Pro" rel="stylesheet"-->


</head>

<body ng-app="myApp">

    <ng-view id="container" >
    </ng-view>

    <div class="print_ignore" id="menu_bar">
        <span class="titel"><img src="img/cosider.png"> </span><br/>
        <div class="inner relative">
            <ul id="main-menu">
                <li class="parent">
                    <a href="#dashboard"><i class="fa fa-pie-chart"></i> Accueil</a>
                </li>


                <li class="parent">
                    <a><i class="fa fa-bookmark-o"></i> Systeme Documentaire QSE</a>
                    <ul class="sub-menu">
                        <li><a href="#manuale"><i class="icon">&#61632;</i>  Manuale</a></li>
                        <li><a href="#form"><i class="icon">&#61827;</i>  Formulaire</a></li>
                    </ul>
                </li>

                

                <li class="parent">
                    <a href="#annuaire"><i class="fa fa-address-book-o"></i> Annuaire</a>
                </li>

                <li class="parent">
                    <a href="#mail"><i class="fa fa-envelope-o"></i> Messagerie</a>
                    
                    <ul class="sub-menu">
                        <?php
                        foreach ( $fw->fetchAll("SELECT * FROM user ") as $row) {
                            if ((($row->name !="") and ($row->name !=NULL)) or (($row->fname!="") and ($row->fname!=NULL)))
                            echo "<li><a class='ld' href='javascript:void(0)' onclick=\"javascript:chatWith('$row->id','$row->name $row->fname')\"><i class='icon'>&#61669;</i> $row->name $row->fname</a></li>";
                        }
                        ?>
                    </ul>
                </li>

                <li class="parent">
                    <a href="#support"><i class="fa fa-support"></i> Support</a>
                    <ul class="sub-menu">
                        <li><a class="ld" href="#faq_grh"><i class="fa fa-group"></i> EGC Grh</a></li>
                        <li><a class="ld" href="#faq_paie"><i class="fa fa-eur"></i> EGC Paie</a></li>
                        <li><a class="ld" href="#faq_compta"><i class="fa fa-bank"></i> EGC Compta</a></li>
                        <li><a class="ld" href="#faq_stock"><i class="fa fa-shopping-cart"></i> EGC Stock</a></li>
                        <li><a class="ld" href="#support"><i class="fa fa-comments"></i> Ticket</a></li>
                        
                    </ul>
                </li>

                <li class="parent">
                    <a><i class="fa fa-vcard-o"></i> <?=($_SESSION['user']); ?></a>
                    <ul class="sub-menu">
                        <li><a class="ld" href="settings"><i class="icon">&#61573;</i> Param&egrave;tre</a></li>
                        <li><a class="ld" href="list_utilisateur"><i class="icon">&#61632;</i> Utilisateur</a></li>
                        <li><a class="ld" href="debug"><i class="icon">&#61553;</i> Debug</a></li>
                        <li><a class="ld" href="register"><i class="icon">&#61474;</i> Register...</a></li>
                        <li><a class="ld" href="bug"><i class="icon">&#61832;</i> Signal&eacute; un bug</a></li>
                        <li><a href="#"  onClick="if (confirm('Etre vous sure de changer l\'activit&eacute; ?')){location='bin/select_activity.php';}"><i class="icon">&#61666;</i>  Changer l'activit&eacute;</a></li>
                        <li><a href="#"  onClick="if (confirm('Etre vous sure de fermer la session ?')){location='?logout';}"><i class="icon">&#61457;</i>  D&eacute;connection</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>

</body>
</html>
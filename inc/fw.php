<?php
// date_default_timezone_set('UTC');
//date_default_timezone_set('Africa/Algiers');
//setlocale(LC_MONETARY, 'dz_DZA');
//session_start();

// Prepart connexion à la base de données
require_once('fw_set.php');

class FireWorks{

    private static $databases;
    private $connection;
    public $tb_user = "user";
    public $tb_log  = "log";
    public $telegram_api;
    public $telegram_id;


    public function __construct($connDetails){
        if(!is_object(self::$databases[$connDetails])){
            list($host, $user, $pass, $dbname) = explode('|', $connDetails);
            $dsn = "mysql:host=$host;dbname=$dbname";
            self::$databases[$connDetails] = new PDO($dsn, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }
        $this->connection = self::$databases[$connDetails];
    }

    // RUN SQL =========================================================
    public function fetchAll($sql){
        $args = func_get_args();
        array_shift($args);
        $statement = $this->connection->prepare($sql);
        $statement->execute($args);
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    // LOG =============================================================
    public function log($msg)
    {
        $sql = "INSERT INTO $this->tb_log (ip,username,msg) VALUES ( '$_SERVER[REMOTE_ADDR]','".(isset($_SESSION['user']) ? $_SESSION['user']->email : "Guest")."','".htmlentities($msg)."')";
        $this->fetchAll($sql);
    }

    // TELEGRAM ========================================================
    public function telegram($message)
    {
        $message = htmlentities($message);
        $result = file_get_contents("https://api.telegram.org/bot$telegram_api/sendMessage?chat_id=$telegram_id&text=$message");
        //$result = json_decode($result, true);
    }

    // AVATAR ==========================================================
    public function gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    // LOGIN ==========================================================
    public function login( $username, $password ) {
        global $_SESSION;
        $username = htmlentities($username);
        $password = sha1(htmlentities($password));
        $sql = "SELECT * FROM $this->tb_user WHERE ( username='$username' OR email='$username' ) AND password='$password'";

        $result =  $this->fetchAll($sql);
        $ret = null;
        if (isset($result[0]) ){
            unset($result[0]->password);
            $result[0]->gravatar = $this->gravatar($result[0]->email);
            $_SESSION['user'] = $result[0];

            session_cache_limiter('private');
            session_cache_expire(60);                   // set the cache expire to 5 minutes
            
            $this->log( "SIGNIN ".$username ) ;
            $ret = true;
        }else{
            $this->log( "ACCESS DENIED / $username / $password / " ) ;
            session_destroy();                          // destroy last session
            $ret = false;
        }

        return $ret;
    }

    public function islogin(){
        if (isset($_SESSION['user']))
            return true;
        else
            return false;
    }

    // LOGOUT =========================================================
    public function logout( ) {
        global $_SESSION;
        if (isset($_SESSION['user']))
        {
            $username=$_SESSION['user']->username;
            $this->log( "LOGOUT / $username / " ) ;
            $_SESSION['user'] = null;
            unset($_SESSION['user']);
            session_destroy();
            return true;                          // destroy last session
        }else return false;
    }
}
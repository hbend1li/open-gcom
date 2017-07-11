<?php
//define('DB_MAIN', 'host|username|password|bdd');

define('DB_MAIN', '127.0.0.1|root||gcom');
$fw = new FireWorks(DB_MAIN);

$fw->tb_user      = "user";
$fw->tb_log       = "logging";
$fw->telegram_api = "156659332:AAFCyXi94dL02gXaHlzRGw7Mk9WZsfMMN1A";
$fw->telegram_id  = "127969204";

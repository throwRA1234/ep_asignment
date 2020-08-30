<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    //since this file is prepended, this is the only place where we need to start a session
}
//this file should load all the components that need a constant presence
require_once('/var/www/html/bootstrap_proj/base/config.php');
require_once('/var/www/html/bootstrap_proj/base/database.php');
require_once('/var/www/html/bootstrap_proj/base/logger.php');
require_once('/var/www/html/bootstrap_proj/base/utils.php');
require_once('/var/www/html/bootstrap_proj/app/parts/header.php');
//load all the API classes here, would be better to use some autoloader
foreach (glob("/var/www/html/bootstrap_proj/app/api/*.php") as $filename) {
    require_once($filename);
}
require_once('/var/www/html/bootstrap_proj/base/api.php');

Class AssetLoader {

    public static function addAssets($path){
        foreach (glob($path."/css/*.css") as $css) {
            echo "<link type='text/css' rel='stylesheet' href='$css'>\n";
        }

//reverse direction of foreach, so that bootstrap isn't loaded before jQuery
        foreach (array_reverse(glob($path."/js/*.js")) as $js) {
            echo "<script type='application/javascript' src='$js'></script>\n";
        }
    }

}

//make sure we always have jquery and css classes 
$path = '../assets';
if(getcwd() != '/var/www/html/bootstrap_proj/app') {$path = 'assets';}
AssetLoader::addAssets($path);

?>


<?php

echo 'begin page<br>';
/*
 * Let's try to discern which includes and functions we actually need
 */

date_default_timezone_set($tz); //need to pull this from config    
define('DB_HOST', $site->config['database']['hostname']); // set database host
define('DB_USER', $site->config['database']['username']); // set database user
define('DB_PASS', $site->config['database']['password']); // set database password
define('DB_NAME', $site->config['database']['database']); // set database name
define('SEND_ERRORS_TO', $site->config['database']['errors']); //set email notification email address
define('DISPLAY_DEBUG', $site->config['database']['display_error']); //display db errors?
define('DB_COMMA', '`'); // sql comma thingy 
define('COMMENT', $settings['templatecomments']); // show template comments or not 
define('TIME_NOW', time()); //time stamp
define('FORMAT_TIME', date($time_format)); // this should be the user time format

if (!defined('DOC_ROOT')) {
    // define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
    define('DOC_ROOT', realpath(dirname(__FILE__)));
}

//echo 'doc root' . DOC_ROOT;
require DOC_ROOT . '/includes/class.dbquick.php';
//require DOC_ROOT . '/includes/class.objects.php';  // and its subclasses ... now the db object has gone do we need this ?
//require DOC_ROOT . '/includes/config.php'; // get config
include DOC_ROOT . '/includes/settings.php'; // get settings 
//$site->config = &$config; // load the config
$site->settings = &$settings; // load settings
//$time_format = "h:i:s A"; // default time settings should get from Auth
//$tz = $site->settings['server_tz'];
$file = 'log.txt';
/*
 * Now let's try to add a default admin
 */

//print_r($_SERVER);

if (!empty($_SERVER['REMOTE_ADDR'])) {
    //echo 'not empty!';
    $newuser['nid'] = getnid();
    //$password = md5($_POST['password'].SALT);
    $newuser['username'] = 'admin';
    $newuser['password'] = md5('P@ssw0rd' . SALT);
    $newuser['email'] = 'admin@email.com';
    $newuser['ip'] = $ip;
    $newuser['loc'] = 'US';
    $newuser['sex'] = 1;
    $newuser['regdate'] = time();
    $newuser['theme'] = $site->settings['theme_path'];
    echo '<br>newuser : ';
    print_r($newuser);
    echo '<br>';
    // die();
    $person = $newuser['username'] . ' ' . FORMAT_TIME . ' ' . $ip;
    // Need a better function for this
    // log_to($file, $person);
    echo '<br> person ';
    echo $person . '<br>';
    print_r($person);
    echo ' <br> try insert';
try {
    $database->insert("users", $newuser);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";    
}
//echo 'error set <br>';
    echo '<br> inserted? ';
     print_r($newuser);
    // die();
}

function getnid() {
    srand(time());
    return md5(rand() . microtime());
}

function getip() {
    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
        return $_SERVER["REMOTE_ADDR"];
    } else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
        return $_SERVER["HTTP_CLIENT_IP"];
    } else if (array_key_exists('HTTP_X_REAL_IP', $_SERVER)) {
        return $_SERVER ['HTTP_X_REAL_IP'];
    } else {
    return "Unknown";
    }
}

function log_to($file, $info) {
    // log stuff
    //die("info = ".$info." file = ".$file); 
    if (!strrpos($info, "\r\n")) {
        $info .= "\r\n";
    }
    file_put_contents($file, $info, FILE_APPEND);
    chmod($file, 0666);
}
<?php

echo '.begin page<br>';
/*
 * Let's try to discern which includes and functions we actually need
 */

if (!defined('DOC_ROOT')) {
    // define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
    define('DOC_ROOT', realpath(dirname(__FILE__)));
}

echo '  ..doc root' . DOC_ROOT . '.. <br>';
require DOC_ROOT . '/includes/class.dbquick.php';
require DOC_ROOT . '/includes/master.inc.php'; // do login and stuff
//require DOC_ROOT . '/includes/class.objects.php';  // and its subclasses ... now the db object has gone do we need this ?
//require DOC_ROOT . '/includes/config.php'; // get config
include DOC_ROOT . '/includes/settings.php'; // get settings 
//$site->config = &$config; // load the config
echo '  ..included.. <br>';
$site->settings = &$settings; // load settings
echo '  ..settings ';
print_r($settings);
echo '..<br>';
//$time_format = "h:i:s A"; // default time settings should get from Auth
//$tz = $site->settings['server_tz'];
// $file = 'log.txt';
/*
 * Now let's try to add a default admin
 */

//print_r($_SERVER);
echo '  ..adding user<br>';
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
    $database->insert("users", $newuser);

//echo 'error set <br>';
    echo '<br> inserted? ';
    print_r($newuser);
    // die();
}

<?php

/*
 * Let's try to discern which includes and functions we actually need  : zer0
 */
echo '<h1>.begin page</h1>';
define('SPF', true); //is this needed?  : zer0
global $ret; //is this needed? probably a return?  : zer0

if (!defined('DOC_ROOT')) {
    // define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
    define('DOC_ROOT', realpath(dirname(__FILE__)));
}

echo '  <h2>..doc root' . DOC_ROOT . '.. </h2>';

// Not a good include
// because it requires authentication 
// that cannot be achieved without a user in the db
//   time for more copy pasta  : zer0
// require DOC_ROOT . '/includes/master.inc.php'; // do login and stuff
 
// Begin bringing in what is needed from master.inc  : zer0
// *****************************************************************************

require DOC_ROOT . '/includes/class.dbquick.php';
require DOC_ROOT . '/includes/functions.inc.php';  // spl_autoload_register() is contained in this file
require DOC_ROOT . '/includes/class.objects.php';  // and its subclasses ... now the db object has gone do we need this ?
require DOC_ROOT . '/includes/config.php'; // get config
include DOC_ROOT . '/includes/settings.php'; // get settings 
echo '  <h2>..included.. </h2>';
$site->config = &$config; // load the config
$site->settings = &$settings; // load settings
echo '  <h2>..configs and settings.. </h2><br>  ..';
print_r($settings);
echo "<br>";
print_r($config);
echo '<br>  ..<br>';
$time_format = "h:i:s A"; // default time settings should get from Auth
$tz = $site->settings['server_tz'];

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
define('GIG', 1073741824); //huh?  : zer0

const SALT = 'insert some random text here';
$database = new db();

if (get_magic_quotes_gpc()) {
    $_POST = fix_slashes($_POST);
    $_GET = fix_slashes($_GET);
    $_REQUEST = fix_slashes($_REQUEST);
    $_COOKIE = fix_slashes($_COOKIE);
}
if ($site->settings['session'] === "0") {

    DBSession::gc(CLEAR); // delete old sessions depends on settings if no sql events do this line 
    //echo '<br> cleared old';
}

// Initialize our session
//echo '<br> Register session';
DBSession::register(); // register the session
session_name('gamecp');
session_start();
$id = session_id();
$_SESSION['userid'] = intval($Auth->id);
$_SESSION['nid'] = $Auth->nid;
$_SESSION['steamid'] = '';
DBSession::read($id);

// *****************************************************************************
// End bringing from master.inc : zer0

// $file = 'log.txt';
/*
 * Now let's try to add a default admin  : zer0
 */

//print_r($_SERVER);
echo '  <h2>..adding user</h2>';
if (!empty($_SERVER['REMOTE_ADDR'])) {
    //echo 'not empty!';
    $newuser['nid'] = getnid();
    //$password = md5($_POST['password'].SALT);
    $newuser['username'] = 'admin';
    $newuser['password'] = md5('P@ssw0rd' . SALT);
    $newuser['theme'] = $site->settings['theme_path']; //cannot be defaulted in db? i disagree. will change later : zer0
    $newuser['level'] = 'admin';
    $newuser['ip'] = $ip;
    $newuser['currentip'] = $ip;
    $newuser['regdate'] = time();
    $newuser['lastseen'] = time();
    $newuser['email'] = 'admin@email.com';
    $newuser['nick'] = 'nickname'; // TBD
    $newuser['loc'] = 'US';
    $newuser['sex'] = 1;
    echo '  <h3>..newuser</h3>';
    print_r($newuser);
    echo '<br>';
    // die();
    $person = $newuser['username'] . ' ' . FORMAT_TIME . ' ' . $ip;
    // Need a better function for this : zer0
    // log_to($file, $person);
    echo '  <h3>..person</h3>';
    echo $person . '<br>';
    print_r($person);
    echo '  <h3>..try insert</h3>';
    $database->insert("users", $newuser);

//echo 'error set <br>';
    echo '  <h3> ..inserted.</h3>';
    print_r($newuser);
    echo 'Admin account created.';
    echo '<h1>Setup complete.</h1> Please do try logging in as admin. If you are able to sign in successfully as admin, please do change the admin username and password and delete this file.';
    // die();
}

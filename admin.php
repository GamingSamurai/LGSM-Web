<?php
require 'includes/master.inc.php'; // required
require 'includes/functions_admin.php'; //admin functions 
//echo php_uname('a');
if (!$_SERVER['HTTP_REFERER']) { 
		redirect ($site->settings['url']."/index.php");
	} 
		
if (!$_SERVER['HTTP_REFERER'] === $site->settings['url']."/index.php" || !$_SERVER['PHP_SELF']) {
		redirect ($site->settings['url']."/index.php");
	}
	if( $Auth->level <> 'admin' )
{
	//the user is not an admin
	redirect ("index.php");
		
}
	echo 'you got in';
	
?>

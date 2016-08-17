<?php
header('Access-Control-Allow-Origin: *');
if(!$_GET) { 
echo get_boottime() . "\n";
exit;
}
//print_r($_GET);
if ($_GET['action'] == 'boottime') {
	echo get_boottime();
	exit;
}
if ($_GET['user']) {
	$user= strtolower($_GET['user']);
	echo 'We will set the user to '. $user;
}

function get_boottime() {
    $tmp = explode(' ', file_get_contents('/proc/uptime'));
    $then = date("Y-md H:i:s",intval($tmp[0]));
    
$days = floor($tmp[0]/86400);
$hours = floor($tmp[0] / 3600);
$mins = floor(($tmp[0] - ($hours*3600)) / 60);
$secs = floor($tmp[0] % 60);

if($days>0){
          
          $hours = $hours - ($days * 24);
          $hrs = str_pad($hours,2,' ',STR_PAD_LEFT);
          
          $return_days = $days." Days ";
          
     }
     else{
      $return_days="";
      ;
      $hrs = $hours;
     }

     
     $sec = str_pad($secs,2,'0',STR_PAD_LEFT);
echo 'Game Server has been running for '. $return_days.$hrs." hours ".$mins." Mins";

}



?>

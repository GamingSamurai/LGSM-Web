<?php
//category.php
define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));

require DOC_ROOT.'/includes/master.inc.php'; // do all sorts of stuff
require_once  DOC_ROOT.'/gameq/GameQ.php'; //load gameq !
$gq = new GameQ();
//print_r($Auth);
$template = new Template;
//die('<br>'.DOC_ROOT.' after load');
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
$per_page = $settings['per_page'];
if (isset($_GET['activetab'])) 
{
	// find out what tab to display
	$activetab = intval($_GET['activetab']);
	//echo $activetab;
}		
else {$activetab = 0;}

if($Auth->loggedIn()) 
           {
			  
			   $name = $Auth->username;
			   $level = $Auth->level;
			   $nid = $Auth->nid;
			   
			   
			   if ($Auth->level === 'user') {
				  				   
			   $login = $template->load($page['template_path'].'member.html', COMMENT);
		   }
		   elseif ($Auth->level === 'admin') {
			   $login = $template->load($page['template_path'].'admin.html', COMMENT) ;
		   }
		   }
						   
	else
				{
					$name ="Guest";
					$login = $template->load($page['template_path'].'guest.html', COMMENT) ;
					$level = 'guest';
					// do you are not logged in and publish
					
				}
				
	if ($name === 'Guest') {
		echo 'Silly fuck face you need to log in !';
		die();
	}
echo $Auth->id.'<br>';			
if ($_GET['id']) {
	$lookat = $_GET['id'];
	print_r($_GET);
	echo '<br>look up user id '.$lookat;
}
else {
	
	$lookat = $Auth->id;
}	
//echo 'we are running with the template class and looking for  game servers<br>';
$sql = "select * from servers where uid ='".$lookat."'"; // we have the users servers ... now display paid for (running) and non paid for via a template
//echo $sql.'<br>';
echo '<br>now fire the ip and port to gameq and get the info back<br> ';
$server_result = $database->num_rows($sql);
//foreach ($server_result as $row)
	//{
		//echo 'we are doing this '. $row['service_type'].'<br>';
		//print_r ($row);
//}
switch ($server_result){
//echo '<br>'.$server_result.'<br>';

	case 1:
	$server_result = $database->get_results($sql);
	foreach ($server_result as $row){
		$servers[$row['Service_name']] = array($row['service_type'], $row['ip'], $row['port']);
		print_r($servers);
	}
	$gq->addServers($servers);
		break;
	default:
			//echo '<br><b></b>we hit more than 1</b><br>';
			//$servers= array();
			
			$server_result = $database->get_results($sql);
		foreach ($server_result as $row){
			$result = count($servers);
			echo 'we are doing this '. print_r($row).'<br>';
			echo ' result<br>';
			if ($result >0){
			
				$servers[$row['Service_name']] = array($row['service_type'], $row['ip'], $row['port']);
			
				}
		else {
						$servers = array(
				$row['Service_name'] => array($row['service_type'], $row['ip'], $row['port'])
				);
			}
			echo '<br>';
									
		}
		$gq->addServers($servers);
		break;
}
		//print_r ($servers);
		
		//echo 'the array contains '.count($servers).' data sets';
		//die();
		
		echo '<br> now getting responses<br>';
		
    
// You can optionally specify some settings
$gq->setOption('timeout', 200);


// You can optionally specify some output filters,
// these will be applied to the results obtained.
$gq->setFilter('normalise');
$gq->setFilter('sortplayers', 'gq_ping');

// Send requests, and parse the data
$results = $gq->requestData();
echo '<b>the result contains '. count($results).' data set(s)</b><br>';
foreach ($results as $key => $value) {
	echo '<br>Key  = '.$key.'<br><br>';
	$server_name = fixname($value['gq_mod'],$value['gq_mapname']);
    //print_r($value);
    //echo '<br>The host is '.$value['gq_hostname'].' and has '.$value['max_players'].' maximum players currently there are '.$value['gq_numplayers'].' online<br>';
    echo '<table style= "width:30%;border:1px solid #000;font-family:Tahoma,;">
    <tr style="background:grey;color:#fff;"><td colspan="3" style="width:100%;">
     
    <span style="float:right;padding-right:2%;">'.$server_name[0].
    '&nbsp;<img style ="vertical-align:middle;" src="'.$server_name[1].'" alt="'.$server_name[0].'"></span></td></tr>
    ';
     if ($value['gq_password'] == 1) {
		$password ='<span style="padding-left:5px;">Password Protection  <b>Yes</b></span>';
	}
	else {
		 $password ='<span style="padding-left:5px;">Password Protection  <b>No</b></span>';
	}
	$online ='<span style="padding-left:5px;">Online Players <b>'. $value['gq_numplayers'] .'/'.$value['gq_maxplayers'].'</b></span>';
    echo '<tr><td colspan="3" style="font-weight:bold;padding-left:5px;">'.$value['gq_hostname'].'&nbsp;
    <span style="float:right;margin-right:1%;">
    <span style="display:inline-block;float:right; position:relative; top:1px; width:8px; height:8px; background-color: #9fff7d;; border-radius:4px; border:1px solid black; box-shadow:inset 0px 0px 1px 1px #02BC00;"></span></span>';
    echo '<tr><td> <span style=padding-left:5px;">Server Map&nbsp;<b>'.$value['gq_mapname'].'</b></span>
    
    <br>'.$password.'<br>'.$online.'</td><td><span style="float:right;padding-right:2%;font-weight:bold;">    <img style="width:150px;height:113px;" src="'.$server_name[2].'"></span></td></tr>';
   
    
    
	
    if ($value['gq_numplayers'] >0 ){
		echo '<tr> <td colspan ="3">Current Players </td></tr>';
		echo '<tr ><td colspan= "3" style="padding-left:2%;padding-right:2%;"><table style="width:100%;border:1px solid #000;padding-bottom:5px;"><tr><td></td> </tr>';
		echo '<tr><th>Name</th><th>Time Online</th><th>Score</th></tr>';
		$test = $value['players'];
		  foreach ($test as $p => $name) {
			  $rt = $name['time'];
			  $rt = gmdate("H:i:s", $rt%86400);
			echo '<tr style="text-align:center"><td style="text-align:center">'.$name['name'].'</td><td>'.$rt .'</td><td>'.$name['score'].'</td></tr>';
			  
		}
		echo '</table></br></td></tr>';
	}
	echo '<tr style="background:grey;color:#fff;text-align:center;"><td colspan="3">Lightsound Studios Game Stats</td></tr></table>';
}


function fixname($name,$map)
{
	// fix gq mod name
	switch ($name) {
		case "garrysmod":
		//$name ="Garry's Mod ";
		$testa = array('Garry\'s Mod','icons/garrysmod.png', 'icons/'.$map.'.jpg'); 
		return $testa;
		case "cstrike":
		$testa = array ('Counter-Strike: Source','icons/css.png','icons/'.$map.'.jpg');
		return $testa;
		default:
		return $name;
	}
function get_password ($value) {
	// get if the server has a password
	 if ($value['gq_password'] == 1) {
		echo '<tr> <td colspan ="3">Server is password protected</td></tr>';
	}
	else {
		echo '<tr> <td colspan ="3">Server is open to players</td></tr>';
	}
}	
}
?>

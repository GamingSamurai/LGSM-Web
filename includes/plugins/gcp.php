<?php
function gcp_info()
{
		return array(
		"name"          => 'Game Control Panel',
		"description"   => 'This adds a game control panel,as a tab a to page, the current options are the main index or the User Control Panel<br>non tabbed sites are not supported',
		"download_url"  => 'http://noideersoftware.co.uk/downloads',
		"author"        => 'NoIdeer Software',
		"authorsite"    => 'http://noideersoftware.org.uk',
		"version"       => '1.0.0.9',
		"vetted"        => '1',
		"run_on"        => '1.*',
		"returns"       => '#gcp#'
	);
	
}

function gcp_run_user()
{
	// each run function needs a tail in this example it is the index file
	global $page, $database, $settings,$Auth; // add the output variable ... curently $page note we have used the existing database/settings classes rather than a new ones
	require DOC_ROOT.'/includes/functions_admin.php';
	require DOC_ROOT.'/gameq-2/GameQ.php';
	$gq = new GameQ(); 
	$page['sbox'] = 'hello there';
	//$demo['sbox'] = 'hello';
	$sql = "select * from games where uid = ".$Auth->id." and active = 1"; // get servers for this user 
	$astats = $database->get_results($sql);
	//print_r($site);
	//echo 'settings '.$settings['game_server'];
	
	$area = 0; //the module you wish to run the plugin on you can use this in the later switch statements
	$template = new Template; // load a template class    note all classes are open to all plugins
	switch (AREA)
	{
		/* note AREA has been defined already
		 * so using a switch statement you can run the same plugin across different modules/areas
		 * this allows you to produce different content per module/area using the same plugin
		 * the plugin class will work out which function to run
		 * example if you have both test_run_index and test_run_category it works it out
		 * becareful of the AREA define, if you do not have 'that' file attached to 'that' area .. it will fail  
		 * however the class should never call it phew
		 */  
	case 0:
	//print_r($Auth);
	
	if(empty($Auth->id)) {goto stuff;};	
	$template->load($page['template_path'].'gcp.html'); // load your template
	$installed = shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=getinstalled'");
	$jim = explode(':',$installed);
	//print_r ($jim);
	$demo['installed'] = 'Edit an installed Server &nbsp;<select class="text"><option>Choose Server</option>';
	foreach ($jim as $expire) {
	if (empty($expire)) {break;}
	$expire =str_replace('_',' ',$expire);
	$demo['installed'].= '<option value ="'.$expire.'">'.$expire.'</option>';
	}
	$demo['installed'] .= '</select>';
	$caninstall = shell_exec("wget -O - --quiet --no-check-certificate 'http://lightsoundstudiosuk.co.uk/exc.php?user=".strtolower($Auth->username)."&action=getcaninstall'");
	$jim = explode(':',$caninstall);
	$demo['install'] = 'New Install &nbsp;<select class="text">';
	foreach ($jim as $expire) {
	if (empty($expire)) {break;}
	$expire =str_replace('_',' ',$expire);
	$demo['install'].= '<option value ="'.$expire.'">'.$expire.'</option>';
	}
	$demo['install'] .= '</select>';
	$running = shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=running'");
	$jim = explode(';',$running);
	//usort($jim, "cmp");
	$online_row = new Template;
	$demo['running'] = 'Running &nbsp;<select class="text">';
	$p = 0;
	$xs = array();
	foreach ($jim as $expire) {
	if (empty($expire)) {break;}
	
	$expire =str_replace('_','',$expire);
	$todo = explode('?',$expire);
	
	$xs[$p]['server_name'] = name($todo[1]);
	$xs[$p]['start_time'] = $todo[2];
	$xs[$p]['port']= filter_var($todo[1], FILTER_SANITIZE_NUMBER_INT)*-1;
	$typeclip = strpos ( $todo[1] , '-');
	$xs[$p]['type'] = substr ($todo[1] ,0 ,$typeclip ); 
	$p++;
	
	//print_r( $todo);
	//echo '<br>';
	$demo['running'].= '<option value ="'.$todo[1].'">'.name($todo[1]).'@ '.$todo[2].'</option>';
	
	
	}
	usort($xs,"cmp"); // sort by date
//print_r($xs);
$gameservers=array();
$gameresults=array();
foreach ($xs as $serverlist) {
	
	//echo $sql;
	$servers = array(
    array(
    'id' => $serverlist['port'],
    'type' =>'source', 
    'host' =>'noideersoftware.uk:'.$serverlist['port']),
      
);
$gameservers[] =  array(
    'id' => $serverlist['port'],
    'type' =>'source', 
    'host' =>'noideersoftware.uk:'.$serverlist['port']);
    
	

    /* here we build the array to query against after gameq has done it's stuff
     * use the port element against the gameq's ID in a for each loop
     * need building a second for each to populate the row template
     */ 
    $gameresults[] = array(
		'server_type' => $serverlist['server_name'],
		'date' => $serverlist['start_time'],
		'port' => $serverlist['port'],
		'type' => $serverlist['type'],
		'start_time' => $serverlist['start_time']
		);
	}
	 
	$gq->addServers($gameservers);
    $gq->setOption('timeout', 2); // Seconds
	$gq->setFilter('normalise');
	$results = $gq->requestData(); // get the server status back	
	//print_r($results);
	//add the template to display the server info now we need to loop it
	$temp['path'] = $page['path'];
	foreach ($gameresults as $serverlist){
		if ($results[$serverlist['port']]['gq_online'] == 0) {
			// we have a tmux session but off line ... server crashed
			echo 'Server Crashed Restarting '.strtolower($Auth->username).'/'.$serverlist['port'] ;
			
		}
		//array_column($records, 'first_name');
		if (in_array($results[$serverlist['port']]['gq_port'], array_column($astats,'port'))) {
              //echo "Got record<br>";
}
		 if( $key = array_search($results[$serverlist['port']]['gq_port'], array_column($astats, 'port')) == !false) {
		//echo 'key = '.$key. ' remove port '.$serverlist['port'].'<br>';
		$stopped[] = $astats[$key];
}

		if ($results[$serverlist['port']]['secure'] == 1) {
			// get secure
			//echo $results[$serverlist['port']]['gq_hostname'].' is vac secure<br>';
			$temp['vac'] = 'This server uses Valve Anti Cheat';
		}
		else {
			//echo $results[$serverlist['port']]['gq_hostname'].' is not vac secure<br>';
			$temp['vac'] = 'This server does not use Valve Anti Cheat';
		}
		// if(($key = array_search($serverlist['port'], $astats)) !== false) {
    //unset($astats[$key]);
   //}
	$online_row->load($page['template_path'].'gcp_row.html');
	$demo['total_players'] +=  $results[$serverlist['port']]['gq_numplayers'];
	$page['total_players'] = $demo['total_players'];
	$temp['server_type'] =$serverlist['server_type'];
	$temp['name'] = $results[$serverlist['port']]['gq_hostname'] ;//name($serverlist['server_name']);
	if (empty($results[$serverlist['port']]['players'])) {
			//echo 'no one on '.$results[$serverlist['port']]['gq_hostname'].'<br>';
			$temp['player_list'] = 'No One playing';
			}
	else { 	$temp['player_list'] = get_player_data($results[$serverlist['port']]['players']);	
		}	
			
	$temp['date'] = date("d-m-Y h:i:sa",$serverlist['start_time']);
	$temp['short_date'] = date("d-m-Y",$serverlist['start_time']);
	$temp['online'] = $results[$serverlist['port']]['gq_numplayers'];
	$temp['maxplayers'] = $results[$serverlist['port']]['gq_maxplayers'];
	$temp['map_image'] = $page['path'].'/images/games/'.$results[$serverlist['port']]['gq_mapname'];
	$temp['type'] = $serverlist['type']; 
	$online_row->replace_vars($temp); // add the debug stuff
	$temp['hostname']=$temp['name'];
	$temp['map'] = $results[$serverlist['port']]['gq_mapname'];
	$temp['playlink']= $results[$serverlist['port']]['gq_joinlink'];
	$demo['test1'].= $online_row->get_template(); //glue the rows in - debug
	$online_row->load($page['template_path'].'gcp_a_row.html'); // do the accordian
	$online_row->replace_vars($temp);
	$demo['sbox'].= $online_row->get_template();
	$page['sbox'].= $demo['sbox'];
	$ports ++;
	$slots += $results[$serverlist['port']]['gq_maxplayers']; 
}

//echo count($stopped);
//print_r($s);
stuff:
//echo 'here is game box<br>'.$page['sbox'];
	//die();
	//print_r($gameresults);
	//echo '<br>';
	//print_r($gameservers);
	//echo $demo['total_players'];
	$demo['running'] .= '</select>';
	//echo 'hello';
	//$demo['slots']=$slots;
	$demo['games'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate 'http://lightsoundstudiosuk.co.uk/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=games'")*1024);
	$page['games']=$demo['games'];
	$demo['total'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate 'http://lightsoundstudiosuk.co.uk/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=total'")*1024);
	$page['total']=$demo['total'];
	$demo['web'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate 'http://lightsoundstudiosuk.co.uk/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=web'")*1024);
	$page['web'] = $demo['web'];
	$demo['totalused'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate 'http://lightsoundstudiosuk.co.uk/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=totalused'")*1024);
	$page['totalused'] = $demo['totalused'];
	$demo['mail'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate 'http://lightsoundstudiosuk.co.uk/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=mail'")*1024);
	$page['mail']=$demo['mail'];
	//$used = 'needs to be done';
	//$demo['used'] = $used.'';
	//die( 'this is used - '.$used);
	$demo['res'] = $_SESSION['screen_width'].'x '. $_SESSION['screen_height'];
	$tb = gcp_info(); //do something call a function perhaps
	$demo['version'] = $tb['version']; //create your template array
	$demo['name'] = $tb['name']; //ditto
	$demo['user'] = $Auth->username; // ditto
	$demo['usedports'] = $ports*3;
	$page['usedports']=$demo['usedports'];
	$demo['ownedports'] = $Auth->ports;
	$page['ownedports'] = $demo['ownedports'];
	if ($demo['usedports'] >= $demo['ownedports']) { 
		//$demo['usedports'] ='<span style="color:red;">'.$demo['usedports'].'</span>';
	}
	$page['usedports'] = $demo['usedports'];
	$demo['ownedslots'] = $Auth->slots;
	$page['ownedslots']=$demo['ownedslots'];
	$demo['usedslots'] = $slots;
	$page['usedslots'] = $slots;
	//$demo['used'] = $used;
	
	if (!empty($tb['vetted'])) { $demo['sometext'] .= ' this plugin has passed';} // alter some content
	else { $demo['sometext'] .= ' this plugin as no vet';} 
	$template->replace_vars($demo);  // place the content into the display array 
	$page['gcp'] = $template->get_template(); // add your plugin template to the main page
	//$page['gcp'] ='here we are';
	return $page['gcp'];
	break;
	
	case 2:
	
	$page['gcp'] = 'we are running on the forum index'; // add your plugin template to the main page
	break;
	
	default:
	$page['gcp'] = ''; 
} 
}

function name ($option)
{
	global $database, $Auth;
	//get game name
	//echo $option;
	if (strpos( $option , 'css' )!== false) 
	{
		//echo 'source';
		$int = filter_var($option, FILTER_SANITIZE_NUMBER_INT)*-1; 
		return 'Counterstrike Source';
	}
	elseif (strpos( $option , 'da' )!== false)
	 {
		 $int = filter_var($option, FILTER_SANITIZE_NUMBER_INT)*-1; 
		return 'Double Action';
	}
	elseif (strpos( $option , 'fof' )!== false)
	 {
		 $int = filter_var($option, FILTER_SANITIZE_NUMBER_INT)*-1; 
		return 'Fistful Of Frags' ;
	}
	elseif  (strpos( $option , 'gmod' )!== false)//($option === 'gmod ') 
	{
		$int = filter_var($option, FILTER_SANITIZE_NUMBER_INT)*-1; 
		return 'Garry\'s Mod';
	}
	elseif (strpos( $option , 'nmrih' )!== false) 
	{
		$int = filter_var($option, FILTER_SANITIZE_NUMBER_INT)*-1; 
		return 'No More Room In Hell';
	}
	elseif (strpos( $option , 'tftwo' )!== false) 
	{
		$option = explode("-",$option);
		$int = $option[1] ;//filter_var($option, FILTER_SANITIZE_NUMBER_INT);
		//echo $int.'<br>'; 
		return 'Team Fortress 2';
	}
	else {
		return $option;
	}
}
function get_gameq($user) {
		global $Auth,$database;
		// this sets up none running games
		$sql = "SELECT games.*, users.username FROM `games` left join users on uid = users.id WHERE users.id =" .$Auth->id;
		$games = $database->get_results($sql); //
	}
	function cmp($a, $b)
{
    return strcmp($a['start_time'], $b['start_time']);
}
	function get_player_data($content) {
		// draw the player data
		foreach ($content as $player) {
			// do stuff
			//echo 'Player '.$players['name'].'<br>';
			$players .= '<div style="float:left;width:88%;clear:both;">'.$player['name'].'</div>
			<div style="float:left;width:8%;text-align:right;margin-right:1%">'.$player['score'].'</div>';
		}
		//echo 'hit the player data thingy<br>';
		//echo $test;
		return $players;
	}
?>

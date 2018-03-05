<?php
/* GCP 2 Direct Version of GCP
 * 14-6-2016
 */
   function get_games() {
    require DOC_ROOT.'/includes/functions_admin.php';
	require DOC_ROOT.'/gameq-2/GameQ.php';
	global $page, $database, $settings,$Auth;
	//sleep(4);
	$online_row = new Template; 
	$template = new Template;
	$template->load($page['template_path'].'gcp.html'); // load your template
	$gq = new GameQ();
	$game_record['path']= $page['path'];
	$sql = "select * from games where uid = ".$Auth->id." and active = 1"; // get servers for this user 
	$astats = $database->get_results($sql);
	//print_r($astats);
	//echo '<br>';
	$stopped = $astats;
	//build the array to query
	$installed = shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=getinstalled'");
	$installed = explode(':',$installed);
	$running = shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=running'");
	$running = explode(';',$running); // got tmux sessions
	$p=0;
	$slots = 0;
	foreach ($running as $game) {
	if (empty($game)) {break;}
	
	$game =str_replace('_','',$game);
	$game = explode('?',$game);
	$xs[$p]['start_time'] = $game[2];
	$xs[$p]['port']= filter_var($game[1], FILTER_SANITIZE_NUMBER_INT)*-1;
	$p++;
	}
	usort($xs,"cmp"); // sort by date
foreach ($xs as $server){
	$key = array_search($server['port'], array_column($astats, 'port'));
    if (isset($key))
          {//echo 'found  '.$server['port'].' in '.$key.'<br>';
			$servers[] = array(
		   'id' => $server['port'],
		   'type' =>$astats[$key]['protocol'], 
		   'host' =>$astats[$key]['ip'].':'.$server['port']);  }
			
    else {
			// need to workout what to do
			echo 'could not find '.$server['port'].'<br>Check Array';
			print_r($astats);
			echo '<br>';
			}
		
		}
//print_r($servers);
//die();
    $gq->addServers($servers);
	
    $gq->setOption('timeout', 2); // Seconds
	$gq->setFilter('normalise');
	checkservers:
	$results= array();
	//print_r($results);
	$results = $gq->requestData(); // get the server status back
	
	foreach ($servers as $key=>$serverlist){
		if ($results[$serverlist['id']]['gq_online'] == 0) {
			// we have a tmux session but off line ... server crashed
			//echo 'Server Crashed Restarting '.strtolower($Auth->username).'/'.$serverlist['port'] ;
			//echo '<br>';
			//print_r($results[$serverlist['id']]);
			$count++;
			//print_r($serverlist);
			//sleep(3);
			if ($count = 1  ){
				//$global['sbox'] ='';
				$slots = 0;
				$ports = 0;
				$count=0;
				//goto checkservers;
				}
			else { 
				echo 'Can not relsolve this server ';
				continue;
				}
			
		}
		if (in_array($results[$serverlist['id']]['gq_port'], array_column($astats,'port'))) {
              //echo "Got record for ".$results[$serverlist['id']]['gq_port']."<br> the count level=".$count;
              $online_row->load($page['template_path'].'gcp_row.html'); //load the template
              $page['total_players'] +=  $results[$serverlist['id']]['gq_numplayers']; //global player count
              if ($results[$serverlist['id']]['secure'] == 1) {
				// get secure
				$game_record['vac'] = 'This server uses Valve Anti Cheat';
			}
			else {
				//echo $results[$serverlist['port']]['gq_hostname'].' is not vac secure<br>';
				$game_record['vac'] = 'This server does not use Valve Anti Cheat';
				
			}
			
				$key = array_search($results[$serverlist['id']]['gq_port'], array_column($xs, 'port'));
if ($key !== false) {
    // Found...
    //echo 'key = '.$key.'<br>';
    $game_record['start_time'] = date("D M j Y G:i:s (T)",$xs[$key]['start_time']);
}
				//echo $game_record['start_time'].'<br>'; 
			
           $game_record['name'] = $results[$serverlist['id']]['gq_hostname'] ;
           if (empty($results[$serverlist['id']]['players'])) {
			//echo 'no one on '.$results[$serverlist['port']]['gq_hostname'].'<br>';
			$game_record['player_list'] = 'No One playing';
			}
			else { 
				//echo 'playrs on line';	
				//print_r($results[$serverlist['id']]['players']);
				$game_record['player_list'] = get_player_data($results[$serverlist['id']]['players']);	
			}
			$game_record['online'] = $results[$serverlist['id']]['gq_numplayers'];
			$game_record['maxplayers'] = $results[$serverlist['id']]['gq_maxplayers'];
			if (check_image ($results[$serverlist['id']]['gq_mapname'])) { 
			$game_record['map_image'] = $page['path'].'images/games/'.$results[$serverlist['id']]['gq_mapname'];
		    }
		    else {
				$game_record['map_image'] = $page['path'].'images/games/no_image';
			}
			if ($results[$serverlist['id']]['gq_mod']){
					$game_record['type'] = $results[$serverlist['id']]['gq_mod'];} 
			else {$game_record['type'] = strtolower($results[$serverlist['id']]['gq_name']);} 
			$online_row->replace_vars($game_record);	
			$game_record['hostname']=$game_record['name'];
			$game_record['map'] = $results[$serverlist['id']]['gq_mapname'];
			$game_record['playlink']= $results[$serverlist['id']]['gq_joinlink'];
			$game_record['port'] = $results[$serverlist['id']]['gq_port'];
			$global['test1'].= $online_row->get_template(); //glue the rows in - debug
			$online_row->load($page['template_path'].'gcp_a_row.html'); // do the accordian
			$ports ++;
			$slots += $results[$serverlist['id']]['gq_maxplayers']; 
			
			// remove active games from the full list
			$gkey = array_search($results[$serverlist['id']]['gq_port'], array_column($astats, 'port'));
          if (isset($gkey))
          {
			  //$mc =count($stopped);
			  //echo 'found  '.$game_record['port'].' in '.$gkey.' with a count of '.count($stopped).'<br>';
			  
			  $game_record['file'] = $astats[$gkey]['file'];
			  $game_record['port'] = $astats[$gkey]['port'];
		      $game_record['gpath'] = $astats[$gkey]['install_path'];		
			  unset ($stopped[$gkey]);
			  if (count($stopped === 1)) {
				  //unset($stopped);
				  }
		}
			$online_row->replace_vars($game_record);
			$global['sbox'].= $online_row->get_template();
		}
		
	}
	//if ($count > 0){goto checkservers;}
	$page['games'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=games'")*1024);
	$page['total'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=total'")*1024);
	$page['web'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=web'")*1024);
	$page['totalused'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=totalused'")*1024);
	$page['mail'] = sizeFormat(shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=diskspacedused&option=mail'")*1024);
	$page['usedports'] = $ports*3;
	$page['ownedports'] = $Auth->ports;
	$page['ownedslots'] = $Auth->slots;
	$page['usedslots'] = $slots;
	$template->replace_vars($global);  // place the content into the display array 
	$page['gcp'] = $template->get_template(); 
	//print_r($stopped);
	$mc =count($stopped);
	// do the stopped list
	//echo '<br> getting ready to do stopped count = '.$mc;
	if ($mc < 1) {
		$page['starter_box'].= '<div style="text-align:center;">All Running</div>';
		goto finished;
	}
	//echo '<br>records to set stopped count = ';
	$online_row->load($page['template_path'].'workers/starter.html');
	foreach ($stopped as $stop_game) {
		//process stopped
		//print_r($stop_game);
		$starter['title'] = '&nbsp;Click For Options&nbsp;';
		if ($page['ownedports'] == $page['usedports']) {
			
			//$starter['battr'] ='disabled';
		}
		$starter['file'] = $stop_game['file'];
		$starter['port'] = $stop_game['port'];
		$starter['gpath'] = $stop_game['install_path'];		
		$starter['sname'] = $stop_game['file'].'-'.$stop_game['port'];
		$online_row->load($page['template_path'].'workers/starter.html');
		$online_row->replace_vars($starter);
		$page['starter_box'].= $online_row->get_template();
	}
	finished:
	$template->replace_vars($global);  // place the content into the display array 
	$page['gcp'] = $template->get_template(); 
	return $page['gcp'];
}

function cmp($a, $b)
{
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}
	function get_player_data($content) {
		// draw the player data
		foreach ($content as $player) {
			// do stuff
			//echo 'Player '.$player['gq_name'].' '.$player['player'].'<br>';
			$players .= '<div style="float:left;width:88%;clear:both;">'.$player['gq_name'].'</div>
			<div style="float:left;width:8%;text-align:right;margin-right:1%">'.$player['gq_score'].'</div>';
		}
		//echo 'hit the player data thingy<br>';
		//echo $test;
		return $players;
	}
	
	function check_image($file) {
		// check if file is there
		$file= DOC_ROOT.'/images/games/'.$file .'.png';
		//echo 'looking for '.$file.'<br>';
		if (file_exists ($file)) { 
		//echo $file.'<br>';
		return true;
	}
	else {
		return false;
	}
	}
?>

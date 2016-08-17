<?php
if(empty($_GET['user'])) {die('ran wrong');}
//echo 'Checking for Game '.$_GET['game'].'<br>';
//echo "hello ".$_GET['user'].'<br>';
define("WEB",'/home/'.$_GET['user'].'/public_html');
define("GAMES",'/home/'.$_GET['user'].'/games');
define("TOTAL",'/home/'.$_GET['user']);
define("MAIL",'/home/'.$_GET['user'].'/Maildir');
if (empty($_GET['action'])) {die('nothing to do');}
//if (empty($_GET['option'])) {die('Nothing to do');}
//echo 'Current script owner: ' . get_current_user();
if  ($_GET['action'] == 'ufw') {
		echo 'ufw<br>';
		exec ('sudo ufw status' ,$ufw);
		//print_r($ufw);
		exit;
	}
if ($_GET['action'] =='getinstalled') {
	exec ('sudo -u '.$_GET['user'].' flj d /home/'.$_GET['user'].'/games',$games);
	//print_r($games);
	foreach ($games as $game) {
		echo $game.':';
	}
		exit;
	}
if($_GET['action'] =='getcaninstall') {
	
	// see what we have
	exec ('sudo -u '.$_GET['user'].' flj d /usr/games/steam',$games);
	foreach ($games as $game) {
		echo $game.':';
	}
		exit;
}
if($_GET['action'] == 'running') {
	// lets see what the user is running
	exec('sudo -u '.$_GET['user']." tmux list-sessions -F '#{session_name} #{session_created'}",$output1); //get the tmux output
	//echo 'tmux '.$output1.'<br>';
	$op = tidy_tmux($output1);
	foreach ($op as $game) {
		echo $game;
	}
	//print_r($op);
	exit;
}

if ($_GET['action'] =='gamedetails') {
		//do game service
	    echo 'Total Game stats';
	    exit;
	}
if($_GET['action'] == 'diskspacedused') {
	// get the user disk space
	//echo 'disk space !';
	//echo '<br>sudo -u  quota -u '.$_GET['user'].' -s';
	//exec('sudo -u ' .$_GET['user']. ' quota -u '.$_GET['user'],$output1); //get disk used
	exec('sudo -u '.$_GET['user'].' quota',$output1); //get disk used
	//echo print_r($output1);
	$ds = str_replace(' ',';',trim($output1[2]));
    //echo '<br> array back '.$ds;     

	$ds = explode(';',$ds);
	//echo '<br>Printing DS</br>';
	if ($_GET['option'] == 'games') {
		exec('sudo -u '.$_GET['user'].' du -s '.GAMES,$testout);
	echo $testout[0]; 
	exit;
}
elseif ($_GET['option'] == 'total') {
	echo intval($ds[4]);
   //die('hit total');
	exit;
}
elseif ($_GET['option'] == 'totalused') {
	//echo intval($ds[3]);
	//die('hit total');
	exec('sudo -u '.$_GET['user'].' du -s '.TOTAL,$testout);
		echo $testout[0];
	exit;
}
elseif ($_GET['option'] == 'web') {
		exec('sudo -u '.$_GET['user'].' du -s '.WEB,$testout);
		echo $testout[0];
		exit;
	}
	elseif ($_GET['option'] == 'mail') {
		exec('sudo -u '.$_GET['user'].' du -s '.MAIL,$testout);
		echo $testout[0];
		exit;
	}
exit;
}

if ($_GET['action']== 'slots') {
	//echo 'options = '.$_GET['option'].'<br>';
	//die ('hit slots');

	if ($_GET['option'] == 'all') {
		// get the total amount slots for the user
		exec ('sudo -u '.$_GET['user'].' flj d /home/'.$_GET['user'].'/games',$games); //find the folders
		echo 'we hit all slots<br>';
		print_r($games);
		exit;
	}
	elseif ($_GET['option'] == 'game') {
		/* pull the slots per game
		 * use the get/put variable 'title' to get the slots
		 * if empty get out of here invalid input
		 */ 
		if (empty($_GET['option'])) {die('invalid');}
		 echo 'we hit slots per game';
		 exit;
	}
	else{
		die ('invalid');
	}
}
exit;
exec('sudo -u '.$_GET['user'].' screen -ls',$output);
if(!empty($output[1])) {
//print_r($output);
echo '<br>';
}
exec('sudo -u '.$_GET['user'].' tmux ls',$output1); //get the tmux output
if(!empty($output1)){
//print_r($output1);
}
//echo '<br> showing installs';
exec ('sudo -u '.$_GET['user'].' flj d /home/'.$_GET['user'].'/games',$games);
//print_r($games);
//echo '<br> altered array<br>';
$op = tidy_tmux($output1);
print_r($op);
echo '<br>';
exec ('sudo -u '.$_GET['user'].' /home/'.$_GET['user'].'/games/Counterstrike\ Source/server details',$games1);
print_r($games1);

function tidy_tmux($array) {
	// tidy the array up
	foreach ($array as &$line) {
		// now tidy the line
		$line = str_replace('1 windows',"",$line);
		//echo $line1.'<br>';
		//$line = str_replace('[80x34]',"",$line);
		$line = str_replace('-server',"",$line);
		//$line = str_replace(' (created ',":",$line);
		//$line = str_replace(')',"",$line);
		//$x = strpos($line,'[');
		//$line = substr($line,0,$x-1);
		$delim = strpos($line,' ');
		$line = substr_replace($line,'?',$delim,1);
		$line= '?'.$line;
		$line .=';';
		//echo  'This is a line ('.$delim.') '.$line.'<br>';
		//print_r($array);
		// now config the date time
	}
	return $array;
}
?>

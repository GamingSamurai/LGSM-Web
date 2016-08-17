<?php
/* 
 * this file commands start/stop/restart/validate
 * requires game type
 * requires game port
 * requires run file name (13-06-2016)
 * perhaps everything via post to stop servers being shut down via a remote user
 * ties up with Merlin 'nickname' as no one will know the actual user name
 * all Merlin users will now require a 'nickname'
 * use Merlin's nid to double check
*/
print_r($_GET);
if(empty($_GET['action'])) {
	// die if invalid
	echo 'Die pissy'; 
}
/* here we have check the file name we should send this from the web controler database rather than building it
 * if used on the same server as the game just read the cookies & query the database 
 */
$server = ' /home/'.$_GET['user'].'/games/'.$_GET['type'].'/'.$_GET['type'].'server-'.$_GET['port'];
echo $server.'<br>';
 switch ($_GET['action']) {
	 case 'restart':
		//echo 'sigr';
			// if we have got to crash
			// do tmux kill-session -t session
			echo '<br>'.$server.'<br>';
			exec('sudo -u '.$_GET['user'].$server." restart",$exe,$return);
			print_r($exe);
			echo '<br>';
			echo($return);
		break;
	case 'stop':
		exec('sudo -u '.$_GET['user'].$server." stop",$exe,$return);
		print_r($exe);
					echo '<br>';
			echo($return);
		break;
	case 'start':
		exec('sudo -u '.$_GET['user'].$server." start",$exe,$return);
		print_r($exe);
					echo '<br>';
			echo($return);
		break;	
	}
?>

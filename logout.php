<?PHP
    require 'includes/master.inc.php';
    echo 'start logout';
    $template = new Template;
    //$filename = "user".$Auth->id.".txt";
    //$old = getcwd(); // Save the current directory
    //chdir(DOC_ROOT."/forum");
    //unlink($filename);
    //chdir($old); // Restore the old working directory    
    $Auth->loginUrl = "";
    $kill = $Auth->nid;
    echo '<br>just about to distroy';
    distroy_session($kill,$database);
    echo '<br> db session distroyed';
    $Auth->logout();
    $pms="0";	
    //die ("got here ".$site->settings['url'].'/index.php');
   if(!empty($_SERVER['HTTP_REFERER'])){
       redirect( $_SERVER['HTTP_REFERER']);
	}
	redirect($site->settings['url'].'/index.php');
    
?>

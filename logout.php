<?PHP
    require 'includes/master.inc.php';
    echo 'start logout';
    $template = new Template;
    
    $Auth->loginUrl = "";
    $kill = $Auth->nid;
    echo '<br>just about to distroy';
    distroy_session($kill,$database);
    echo '<br> db session distroyed';
    $Auth->logout();
    $pms="0";	
    
   if(!empty($_SERVER['HTTP_REFERER'])){
       redirect( $_SERVER['HTTP_REFERER']);
	}
	redirect($site->settings['url'].'/index.php');
    
?>

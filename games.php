
<?php
/*
 * main Index file
 * does very little as it does not need to !
 * COMMENT in the template->load function uses the default setting
 * replace COMMENT with either true or false to over ride this
 */ 
 
require 'includes/master.inc.php'; // do login and stuff
include 'includes/plugins/gcp.php';
//menu_items(7,4);

// here would should define all areas/modules currently it is manual !
//gcp_run_user();
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
$template = new Template;
//$our_plugin->get_plugins(basename($_SERVER['PHP_SELF']));
if($Auth->loggedIn()) 
           {
			  
			   $name = $Auth->username;
			   $nick = $Auth->nick;
			   $level = $Auth->level;
			   $nid = $Auth->nid;
			   
			   
			   if ($Auth->level === 'user') {
				menu_items(2);  				   
			   //$login = $template->load($page['template_path'].'member.html', COMMENT);
			   $page['content'] = gcp_run_user();
			   //echo 'content = '.$page['content'];
			   //die();
			   $page['search'] = '';
               $template->load($page['template_path'].'status_box.html');
               $template->replace_vars($page);
               $page['right'] = $template->get_template();
               		   }
		   elseif ($Auth->level === 'admin') {
			   //$login = $template->load($page['template_path'].'admin.html', COMMENT) ;
			   menu_items(4); 
			   $page['content'] = 'admin logged in';
			   $page['search'] = '';
        $page['right'] = '<div id="col" class="noprint">
            <div id="col-in" style="height:200px;" >

                <!-- Links -->
                <h3 style="text-align:center;background:#192666;color:#fff;"><span>Games</span></h3>

                <!--<ul id="links">
                    <li><a href="http://www.virtualmin.com/">Virtualmin</a></li>
                    <li><a href="http://www.webmin.com/">Webmin</a></li>
                </ul>

                <hr class="" /> -->
            <div id="links" style="min-height:100px;padding:5%;">
                    status box here using games
                </div>
            </div> <!-- /col-in -->
        </div> <!-- /col -->

    </div> <!-- /page-in -->
    </div> <!-- /page -->';
		   }
		   
		   }
						   
	else
				{
					$name ="Guest";
					//$login = $template->load($page['template_path'].'guest.html', COMMENT) ;
					$level = 'guest';
					$page['content']= $template->load($page['template_path'].'workers/login.html');
					$page['search'] = '';
					$page['right'] = '<div id="col" class="noprint">
            <div id="col-in" >

                <!-- Links -->
                <h3 style="text-align:center;background:#192666;color:#fff;display:hidden"><span>Status</span></h3>

                <!--<ul id="links">
                    <li><a href="http://www.virtualmin.com/">Virtualmin</a></li>
                    <li><a href="http://www.webmin.com/">Webmin</a></li>
                </ul>

                <hr class="" /> -->
            <div id="links" style="min-height:100px;padding:5%;">
                    You need to be logged in to view system status
                </div>
            </div> <!-- /col-in -->
        </div> <!-- /col -->

    </div> <!-- /page-in -->
    </div> <!-- /page -->';
					
				}
$template->load($page['theme_path'].'templates/template.html',true);
//$page['content'] = 'this is our content';
//print_r($page);
//die();	
$template->replace_vars($page);	    

//$template->replace("xx",FORMAT_TIME);

$template->publish();


?>

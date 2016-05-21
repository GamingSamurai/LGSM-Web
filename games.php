
<?php
/*
 * main Index file
 * does very little as it does not need to !
 * COMMENT in the template->load function uses the default setting
 * replace COMMENT with either true or false to over ride this
 */ 
 
require 'includes/master.inc.php'; // do login and stuff
// here would should define all areas/modules currently it is manual !

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
$template = new Template;
if($Auth->loggedIn()) 
           {
			  
			   $name = $Auth->username;
			   $nick = $Auth->nick;
			   $level = $Auth->level;
			   $nid = $Auth->nid;
			   
			   
			   if ($Auth->level === 'user') {
				  				   
			   //$login = $template->load($page['template_path'].'member.html', COMMENT);
			   $page['content'] = 'user loged in on games tab';
			   $page['search'] = '';
        $page['right'] = '<div id="col" class="noprint">
            <div id="col-in" >

                <!-- Links -->
                <h3 style="text-align:center;background:#192666;color:#fff;"><span>Games</span></h3>

                <!--<ul id="links">
                    <li><a href="http://www.virtualmin.com/">Virtualmin</a></li>
                    <li><a href="http://www.webmin.com/">Webmin</a></li>
                </ul>

                <hr class="" /> -->
            <div id="links" style="min-height:100px;padding:5%;">
                    status box here user games
                </div>
            </div> <!-- /col-in -->
        </div> <!-- /col -->

    </div> <!-- /page-in -->
    </div> <!-- /page -->';
		   }
		   elseif ($Auth->level === 'admin') {
			   //$login = $template->load($page['template_path'].'admin.html', COMMENT) ;
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
					$page['content']= 'you need to login in order to use this site, if you don\'t have your login ID contact your service providor or login below <form method="post" action="login.php" id="input">
                                <div class="column left" style="width:50%;float:left;">
                                    <p><label for="username" style="width:30%">Username</label>
                                        <input type="text" name="username" id="username" size="35" style = "width:50%;display:inline" value="">
                                        
                                    </p>
                                    <p><label for="Password">Password   </label>
                                        <input type="password"  name="password" id="password" style = "width:50%;display:inline"  value="">
                                        <br><span class="validation" style="margin-left:20%;"> passwords are case sensitive</span>
                                    </p>
                                   
                                </div>
                               
                                <div class="clear"></div>
                                <p style ="margin-left:13%;padding-bottom:4%;"><button type="submit" class="button" name="btnlogin" id="input" value="Login">Login</button> 
                                </p>
                            </form>';
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
$template->load($page['theme_path'].'templates/games.html',true);
//$page['content'] = 'this is our content';	
$template->replace_vars($page);	    

//$template->replace("xx",FORMAT_TIME);

$template->publish();


?>

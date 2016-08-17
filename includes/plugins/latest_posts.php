<?php
/* user post plugin
 * first plugin for the yoursite framework
 * TODO 
 * add the function to call 
 * add a way of administering the plugin 
 * versioning for the framework
 * die or return on failure of stuff not being met
 * add peram 
   
*/

//echo 'we are on a plugin !';
/*if ($running === 1) {
we_are_running();
}
elseif ($running === 0) {
		$page['newposts'] = 'plugin disabled ..sorry';
	}
elseif ($running === 2) {
	setup();
}*/
function latest_posts_info()
{
		return array(
		"name"          => 'new posts',
		"description"   => 'displays new community posts',
		"download_url"  => 'https://noideersoftware.co.uk/downloads',
		"author"        => 'NoIdeer Software',
		"authorsite"    => 'http://noideersoftware.org.uk',
		"version"       => '6.6.6',
		"vetted"        => '1',
		"run_on"        => '1.*',
		"returns"       => '#new_posts#'
	);
	
}	

function latest_posts_settings()
{
	// return the settings to admin CP
	global $database, $page, $template, $settings, $level, $site ;
	$sql = 'select * from settings where setting_type = 1';
	
}
	function latest_posts_run_index()
		{
			// this code has been called execute
			global $database, $page, $template, $settings, $level, $site ;
			
				// do the actual code
			switch (AREA)
			{
				case 0:
			$sql = "SELECT posts. *, permissions.*, users.*, topics.topic_id
			FROM  `posts` 
			JOIN users ON posts.post_by = users.id
			JOIN topics ON posts.post_topic = topics.topic_id
			JOIN categories ON topics.topic_cat = categories.cat_id 
			JOIN permissions ON categories.cat_id = permissions.pcat_id	
			 where categories.area = ".FORUM."
			ORDER BY  `post_date` DESC";
                //die ($sql);
				$newposts = $database->get_results($sql);
               $page['newposts'] = "";
		foreach ($newposts as $row)
			{	// decode the row
				
               
				$priv = explode(",",$row[$level]);
				//print_r($settings);
				
						if ($priv[0] === '0')
						{
							goto notforus;
						} 
				if (empty($row['avatar'])) {$row['avatar'] = $site->settings['url'].'/images/default_avatar.png';}
	                //$post_info['postid'] = $pid;
					$post_info['postid1'] = $row['post_id'];
					$post_info['path']= $site->settings['url'];
					$post_info['postdate'] = date('d-m-Y', strtotime($row['post_date']));
					$post_info['posttime'] = date('H:i', strtotime($row['post_date']));
						if (empty($row['nick'])){
							$post_info['username'] = $row['username'];
							}
						else {
							$post_info['username'] = $row['nick'];
							}
					if ($row['level'] === 'banned') { $post_info['username'] = '<strike>'.$post_info['username'].'</strike>';}
					$post_info['post_content'] = html_entity_decode(stripslashes($row['post_content']));
					$post_info['post_subject'] = $subject; // will update when each post has a subject
					$post_info['profilelink']= $row['username']; // later do a link
					$post_info['onlinestatus'] = $online;
					$post_info['avatar'] = $row['avatar'];
					$post_info['subject'] = $row['topic_subject'];
					$post_info['attachments'] = "";
					$post_info['iplogged']='';
					$post_info['signature'] = $row['sig'];
					$template->load($page['template_path']."post.html", COMMENT);
					$template->replace_vars($post_info);
					$page['newposts'].= $template->get_template();
					$i++;
						if ($i >= $settings['new_posts']) {
													
							break;       
						}

					notforus:
					}
					//$page['newposts'] .= 'running a plugin';
					//die ("adding new content");
					break;
					
					case 4:
					$page['newposts'] = "hello";
				}
}		

		function latest_posts_setup() 
		{
			// setup func
			global $page;
			$page['newposts'] = '<form>First name:&nbsp;
  <input type="text" name="firstname">
  <br style="padding:10px">
  <br>
  Last name:&nbsp;
  <input type="text" name="lastname"></form>';
		}
		
			
function latest_posts_disabled()
{
	global $page;
	$page['newposts'] = "Currently, we can not find any posts try back later";
	
}
		?>

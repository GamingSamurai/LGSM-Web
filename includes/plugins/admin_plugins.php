<?php
/* admin edit plugin
 * first plugin for the yoursite framework
 * TODO 
 * add the function to call 
 * add a way of administering the plugin 
 * versioning for the framework
 * die or return on failure of stuff not being met
 * add peram 
*/   

function admin_plugins_info()
{
		return array(
		"name"          => 'Admin Plugin',
		"description"   => 'This Plugin runs on the ACP only',
		"download_url"  => 'http://noideersoftware.co.uk/downloads',
		"author"        => 'NoIdeer Software',
		"authorsite"    => 'http://noideersoftware.org.uk',
		"version"       => '1.0.0.7',
		"vetted"        => '1',
		"run_on"        => '1.*',
		"returns"        => '#admins# as an array'
	);
	
}	
	function admin_plugins_run_index()
		{
			// this code has been called execute
			global $database, $page, $template, $settings, $level, $site ;
			
				// do the actual code
			
			$sql = "SELECT posts. *, permissions.*, users.username, users.avatar, topics.topic_subject, users.nick
			FROM  `posts` 
			JOIN users ON posts.post_by = users.id
			JOIN topics ON posts.post_topic = topics.topic_id
			JOIN categories ON topics.topic_cat = categories.cat_id 
			LEFT JOIN permissions on permissions.pcat_id = `cat_id`	
			 where categories.area = ".FORUM."
			ORDER BY  `post_date` DESC";
                
				$newposts = $database->get_results($sql);
               
		foreach ($newposts as $row)
			{	// decode the row
				
               
				$priv = explode(",",$row[$level]);
				
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
				}
		
		function admin_plugins_setup() 
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
		
			
function admin_plugins_disabled()
{
	global $page;
	$page['newposts'] = "Currently, we can not find any posts try back later";
	
}
		?>

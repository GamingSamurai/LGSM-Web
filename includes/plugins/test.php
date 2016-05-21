<?php
/*
 * example plugin
 * Written by NoIdeer Software
 * this file will hold enough remarks to code a plugin
 * 1) supply a function called <plugin_name>_info
 * 	example if your plugin is called test.php create a function called test_info() this function should contain information about your plugin
 * 	see the example below
 * 2) supply a function to install your plugin settings into the framework settings this is required else the plugin will not be run or may fail
 * 	see the example below
 * 3) run functions
*/  

function test_info()
{
		return array(
		"name"          => 'Test Plugin',
		"description"   => 'This a test plugin to test the plugin class',
		"download_url"  => 'http://noideersoftware.co.uk/downloads',
		"author"        => 'NoIdeer Software',
		"authorsite"    => 'http://noideersoftware.org.uk',
		"version"       => '1.1.0.7',
		"vetted"        => '1',
		"run_on"        => '1.*',
		"returns"       => '#plugin#'
	);
	
}

function test_install()
{
	// you need to add the global database handler
	global $database;
	$table = 'plugins'; // set the table to insert into
	
}

function test_run_index()
{
	// each run function needs a tail in this example it is the index file
	global $page, $database, $settings; // add the output variable ... curently $page note we have used the existing database/settings classes rather than a new ones  
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
		
	$template->load($page['template_path'].'plugins/test.html'); // load your template
	$tb = test_info(); //do something call a function perhaps
	$demo['version'] = ''; //create your template array
	$demo['name'] = ''; //ditto
	$demo['sometext'] = 'Status'; // ditto
	if (!empty($tb['vetted'])) { $demo['sometext'] .= ' this Site runs on the NoIdeer Network';} // alter some content
	else { $demo['sometext'] .= ' this plugin as no vet';} 
	$template->replace_vars($demo);  // place the content into the display array 
	$page['plugin'] = $template->get_template(); // add your plugin template to the main page
	break;
	
	case 2:
	
	$page['plugin'] = 'we are running on the forum index'; // add your plugin template to the main page
	break;
	
	default:
	$page['plugin'] = ''; 
} 
}

function test_disabled()
{
	global $page;
	$page['plugin'] = "";
	
}

function test_run_admin()
{
	// what to do if the plugin runs on the admin script
	//echo 'we are here';
	global $page, $database, $settings;
	$page['plugin'] = 'we could add the game server stuff'; 
}
?>

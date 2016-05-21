<?php
// gallery latest image
if(!defined(DOC_ROOT)) {
	//echo 'This file can not be executed';
	//die();
}
function latest_gallery_info()
{
		return array(
		"name"          => 'Gallery foters',
		"description"   => 'shows random foters from the gallery',
		"download_url"  => 'http://noideersoftware.co.uk/downloads',
		"author"        => 'NoIdeer Software',
		"authorsite"    => 'http://noideersoftware.org.uk',
		"version"       => '70.0.7',
		"vetted"        => '2',
		"run_on"        => '1.*',
		"returns"       => '#gallery#' 
	);
	
}
$sql ='select * from gallery order by time_stamp limit 5';
?>

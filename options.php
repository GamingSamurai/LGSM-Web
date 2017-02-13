<?php
/*
 * options.php
 * 
 * Copyright 2016 jim <jim@noideersoftware.co.uk>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 *  do the game options
 */
require 'includes/master.inc.php'; // do login and stuff
$template = new Template; 
$page['content'] = 'Here we will contain the server maintainence do we need the options buttton on the server display ? it may be good pratice to stop the server before messing with its config.<br>Here is the data coresponding to this server<br>';
$sql = 'Select * from games where uid = '.$Auth->id.' and port = '.$_GET['port'];
$data=$database->get_results($sql);
$running = shell_exec("wget -O - --quiet --no-check-certificate '".$settings['game_server']."/exc.php?user=".strtolower($Auth->username)."&action=running'");
print_r($running);
    $template->load($page['theme_path'].'templates/options.html',true);
$template->replace_vars($page);	    
$template->removephp();
$template->publish();

?>

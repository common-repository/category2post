<?php
/*
Plugin Name: Category 2 Post
Plugin URI: http://nuevosprogramadores.com/category2post/
Description: Allows to include posts from one selected category 2 your post or page body.
Version: 0.1
Author: Pablo Rodríguez
Author URI: http://nuevosprogramadores.com
*/
/*  Copyright 2008  Pablo Rodríguez  (email : prodriguez@nuevosprogramadores.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function category2post($c2p_content)
{
	$c2p_IDCategory = stripslashes(get_option('c2p_IDCategory'));
	$c2p_NumberOfPosts = stripslashes(get_option('c2p_NumberOfPosts'));
	$c2p_ShowAllContent = stripslashes(get_option('c2p_ShowAllContent'));

	$c2p_result = "";
	$c2p_posts = array();
	switch($c2p_NumberOfPosts)
	{
		case 0:
			$c2p_posts = get_posts("category=$c2p_IDCategory");
			break;
		default:
			$c2p_posts = get_posts("numberposts=$c2p_NumberOfPosts&category=$c2p_IDCategory");
			break;
	} 
	if(sizeof($c2p_posts))
	{
		foreach($c2p_posts as $c2p_post) {
			$c2p_result .= "<h3><a href=\"".$c2p_post->guid."\" id=\"".$c2p_post->ID."\">".$c2p_post->post_title."</a></h3>\n";
			if($c2p_ShowAllContent == '0') {
				$c2p_result .= $c2p_post->post_excerpt."\n";
			} else {
				$c2p_result .= $c2p_post->post_content."\n";
			}
		}
	} 
	return preg_replace('|<!--category2post-->|', $c2p_result, $c2p_content);
}

function c2p_add_option_page()
{
	add_options_page('Opciones', 'Category2Post', 'manage_options', 'category2post/options-c2p.php');
}

add_action('admin_head', 'c2p_add_option_page');
add_filter('the_content', 'category2post');
?>

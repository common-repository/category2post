<?php
/*
Description: Category2Post options page
Version: 0.1
Author: Pablo Rodríguez
Author URI: http://nuevosprogramadores.com
*/
$location = get_option('siteurl').'/wp-admin/admin.php?page=category2post/options-c2p.php';
//default options
add_option('c2p_IDCategory', __('3'));
add_option('c2p_NumberOfPosts', __('6'));
add_option('c2p_ShowAllContent', __('0'));

function c2p_getHierarchyLevel($category)
{
	$level = 0;
	while($category->category_parent > 0) 
	{
		$category = get_category($category->category_parent);
		$level++;
	}
	return $level;
}

function c2p_applyPrefixLevel($prefixBase, $level)
{
	$auxPrefix = "";
	for($i=1;$i<=$level;$i++)
	{
		$auxPrefix .= $prefixBase;
	}
	return $auxPrefix." ";
}

if ('process' == $_POST['stage'])
{
	update_option('c2p_IDCategory', $_POST['c2p_IDCategory']);
	update_option('c2p_NumberOfPosts', $_POST['c2p_NumberOfPosts']);
	$showAll = '0';
	if(isset($_POST['c2p_ShowAllContent'])) $showAll = '1';
	update_option('c2p_ShowAllContent', $showAll);
}

$c2p_IDCategory = stripslashes(get_option('c2p_IDCategory'));
$c2p_NumberOfPosts = stripslashes(get_option('c2p_NumberOfPosts'));
$c2p_ShowAllContent = stripslashes(get_option('c2p_ShowAllContent'));

$categories = get_categories('hide_empty=0');
?>
<div class="wrap">
  	<h2><?php _e('Category2Post Options	') ?></h2>
	<form name="form1" method="post" action="<?php echo $location ?>&amp;updated=true">
		<input type="hidden" name="stage" value="process" />
		<table width="100%" cellspacing="2" cellpadding="5" class="editform">
      		<tr>
        		<th scope="row">Category:</th>
        		<td>
        			<select name="c2p_IDCategory">
        			<?php
        				foreach($categories as $category)
        				{
        					$tab = "";
        					if($category->category_parent > 0)
        					{
        						$tab = c2p_applyPrefixLevel('../', c2p_getHierarchyLevel($category));
        					}
        			?>
        					<option value="<?php _e($category->cat_ID); ?>" <?php if($category->cat_ID == $c2p_IDCategory) _e('selected="selected"');?>><?php _e($tab.$category->cat_name); ?></option>
        					<?php
        					$previousCategory = $category;
        				}
        			?>
        			</select>
        		</td>
      		</tr>
      		<tr valign="top">
      			<th scope="row">Number of posts to show:</th>
      			<td><input type="text" name="c2p_NumberOfPosts" value="<?php _e($c2p_NumberOfPosts); ?>" /><br />
      			0 means show all posts</td>
      		</tr>
      		<tr avlign="top">
      			<th scope="row">Show complete posts</th>
      			<td><input type="checkbox" name="c2p_ShowAllContent" value="c2p_ShowAllContent"<?php if($c2p_ShowAllContent == '1') _e('checked="checked"'); ?> /><br />
      			If unchecked it will show an excerpt</td>
      		</tr>
      	</table>
      	<p class="submit">
      		<input type="submit" name="Submit" value="Save options &raquo;" />
      	</p>
      </form>
</div>

<?php
/*
Plugin Name: ShiftThis | Order Pages
Plugin URI: http://www.shiftthis.net/wordpress-order-pages-plugin/
Description: Page Order Management (WP ADMIN > MANAGE > ORDER PAGES).
Author: ShiftThis.net
Version: 0.3
Author URI: http://www.shiftthis.net

CHANGELOG:
February 21, 2007 - Added support for an additional level of sub pages (ie. SubSubPages)
January 28, 2007 - Updates page call for WordPress 2.1 for compatibility with new database structure.

*/
#########CONFIGURATION OPTIONS##################################
$minlevel = 7;  //MINIMUM USER LEVEL TO ACCESS PAGE ORDER
################################################################


function st_pageorder(){
	global $wpdb, $wp_version;
	
	#COUNT THE NUMBER OF PAGES & SUBPAGES
	if($wp_version >= 2.1){
		$allpageposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'page'");
	}else{
		$allpageposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_status = 'static'");
	}
	$pagecount = count($allpageposts);
	$subpages = 0;
	foreach ($allpageposts as $apage){
		if($apage->post_parent >= 1){
			$subarr[] = $apage->post_title;
			}
	}
	$maxsubs = count($subarr); #total number of subpages
	$maxpages = $pagecount - $maxsubs; #total number of parent pages
	//echo '<div class="wrap">Total Pages = '.$pagecount.'<br />Total Parent Pages = '.$maxpages.'<br />Total Child Pages = '.$maxsubs.'</div>';
	?>
	
	<?php
	if ($_GET['update'] == true){
	/*echo '<div class="wrap">';
		foreach($_POST as $key=>$value){
		echo $key.' = '.$value.'<br>';
		}
	echo '</div>';*/
		
		#MOVE PAGE ORDER UP (HIGHER NUMBER)
		if ($_POST['plus'] == true){ 
			$pageup = $_POST['plus']; #The page to move
			$paged = 'page_'.$pageup; 
			$pagein = $_POST[$paged];
			$pageorder = $pagein + 1; #Move the page up 1 step.

			foreach($_POST as $key=>$value){ #Adjust the rest of the pages
				$subbed = strpos($key, 'sub'); #check for subpages
				if($key != 'plus' || $subbed === false){ #dont need the plus value or subpage values
					$neworder = $value; #same value as before
					$pageid = ltrim($key, 'page_'); #gives the pageid
					if ($value == ($pagein + 1)){ #check if the page is directly above
						$neworder = $value - 1; #move the page above down a step
					}
					if ($pageid != $pageup){ #update all the pages but the one we're moving up
					$postquery = "UPDATE $wpdb->posts SET menu_order='$neworder' WHERE ID='$pageid'";
					$wpdb->query($postquery); #update db
					}
				}
			}
			if ($pageorder <= $maxpages){
			#update the page we are moving up
			$postup = "UPDATE $wpdb->posts SET menu_order='$pageorder' WHERE ID='$pageup'";
			$wpdb->query($postup); #updated db
			}
		}
		
		#MOVE PAGE ORDER DOWN (LOWER NUMBER)
		elseif ($_POST['minus'] == true){
			$pagedn = $_POST['minus']; #The page to move
			$paged = 'page_'.$pagedn; 
			$pagein = $_POST[$paged];
			$pageorder = $pagein - 1; #Move the page down 1 step.

			foreach($_POST as $key=>$value){ #Adjust the rest of the pages
				$subbed = strpos($key, 'sub'); #check for subpages
				if($key != 'minus' || $subbed === false){ #dont need the minus value or subpage values
					$neworder = $value; #same value as before
					$pageid = ltrim($key, 'page_'); #gives the pageid
					if ($value == ($pagein - 1)){ #check if the page is directly below
						$neworder = $value + 1; #move the page below up a step
					}
					if ($pageid != $pagedn){ #update all the pages but the one we're moving down
					$postquery = "UPDATE $wpdb->posts SET menu_order='$neworder' WHERE ID='$pageid'";
					$wpdb->query($postquery); #update db
					}
				}
			}
			#update the page we are moving down
			if ($pageorder > 0){
			$postdn = "UPDATE $wpdb->posts SET menu_order='$pageorder' WHERE ID='$pagedn'";
			$wpdb->query($postdn); #updated db
			}
		}
		
		#MOVE SUBPAGE ORDER UP (HIGHER NUMBER)
		elseif ($_POST['subplus'] == true){
			$subpageup = $_POST['subplus']; #The subpage to move
			$paged = 'subpage_'.$subpageup; 
			$pagein = $_POST[$paged];
			$pageorder = $pagein + 1; #Move the subpage up 1 step.

			foreach($_POST as $key=>$value){ #Adjust the rest of the subpages
				$subbed = strpos($key, 'sub'); #check for subpages
				if ($subbed === false){}
				else if($key != 'subplus'){ #dont need the plus value or non-subpage values
					$neworder = $value; #same value as before
					$pageid = ltrim($key, 'subpage_'); #gives the subpageid
					if ($value == ($pagein + 1)){ #check if the subpage is directly above
						$neworder = $value - 1; #move the subpage above down a step
					}
					if ($pageid != $subpageup){ #update all the subpages but the one we're moving up
					$postquery = "UPDATE $wpdb->posts SET menu_order='$neworder' WHERE ID='$pageid'";
					$wpdb->query($postquery); #update db
					}
				}
			}
			#update the page we are moving up
			if ($pageorder <= $maxsubs){
			$subpostup = "UPDATE $wpdb->posts SET menu_order='$pageorder' WHERE ID='$subpageup'";
			$wpdb->query($subpostup); #updated db
			}
		}
		
		#MOVE SUBPAGE ORDER DOWN (LOWER NUMBER)
		elseif ($_POST['subminus'] == true){
			$subpagedn = $_POST['subminus']; #The subpage to move
			$paged = 'subpage_'.$subpagedn; 
			$pagein = $_POST[$paged];
			$pageorder = $pagein - 1; #Move the subpage down 1 step.

			foreach($_POST as $key=>$value){ #Adjust the rest of the subpages
				$subbed = strpos($key, 'sub'); #check for subpages
				if ($subbed === false){}
				elseif($key != 'subminus'){ #dont need the minus value or subpage values
					$neworder = $value; #same value as before
					$pageid = ltrim($key, 'subpage_'); #gives the subpageid
					if ($value == ($pagein - 1)){ #check if the subpage is directly below
						$neworder = $value + 1; #move the subpage below up a step
					}
					if ($pageid != $subpagedn){ #update all the subpages but the one we're moving down
					$postquery = "UPDATE $wpdb->posts SET menu_order='$neworder' WHERE ID='$pageid'";
					$wpdb->query($postquery); #update db
					}
				}
			}
			#update the page we are moving down
			if ($pageorder > 0){
			$subpostdn = "UPDATE $wpdb->posts SET menu_order='$pageorder' WHERE ID='$subpagedn'";
			$wpdb->query($subpostdn); #updated db
			}
		}
		
		#MOVE SUBSUBPAGE ORDER UP (HIGHER NUMBER)
		elseif ($_POST['subsubplus'] == true){
			$subsubpageup = $_POST['subsubplus']; #The subpage to move
			$paged = 'subsubpage_'.$subsubpageup; 
			$pagein = $_POST[$paged];
			$pageorder = $pagein + 1; #Move the subpage up 1 step.

			foreach($_POST as $key=>$value){ #Adjust the rest of the subpages
				$subsubbed = strpos($key, 'subsub'); #check for subpages
				if ($subsubbed === false){}
				else if($key != 'subsubplus'){ #dont need the plus value or non-subpage values
					$neworder = $value; #same value as before
					$pageid = ltrim($key, 'subsubpage_'); #gives the subpageid
					if ($value == ($pagein + 1)){ #check if the subpage is directly above
						$neworder = $value - 1; #move the subpage above down a step
					}
					if ($pageid != $subsubpageup){ #update all the subpages but the one we're moving up
					$postquery = "UPDATE $wpdb->posts SET menu_order='$neworder' WHERE ID='$pageid'";
					$wpdb->query($postquery); #update db
					}
				}
			}
			#update the page we are moving up
			if ($pageorder <= $maxsubs){
			$subsubpostup = "UPDATE $wpdb->posts SET menu_order='$pageorder' WHERE ID='$subsubpageup'";
			$wpdb->query($subsubpostup); #updated db
			}
		}
		
		#MOVE SUBSUBPAGE ORDER DOWN (LOWER NUMBER)
		elseif ($_POST['subsubminus'] == true){
			$subsubpagedn = $_POST['subsubminus']; #The subpage to move
			$paged = 'subsubpage_'.$subsubpagedn; 
			$pagein = $_POST[$paged];
			$pageorder = $pagein - 1; #Move the subpage down 1 step.

			foreach($_POST as $key=>$value){ #Adjust the rest of the subpages
				$subsubbed = strpos($key, 'subsub'); #check for subpages
				if ($subsubbed === false){}
				elseif($key != 'subsubminus'){ #dont need the minus value or subpage values
					$neworder = $value; #same value as before
					$pageid = ltrim($key, 'subsubpage_'); #gives the subpageid
					if ($value == ($pagein - 1)){ #check if the subpage is directly below
						$neworder = $value + 1; #move the subpage below up a step
					}
					if ($pageid != $subsubpagedn){ #update all the subpages but the one we're moving down
					$postquery = "UPDATE $wpdb->posts SET menu_order='$neworder' WHERE ID='$pageid'";
					$wpdb->query($postquery); #update db
					}
				}
			}
			#update the page we are moving down
			if ($pageorder > 0){
			$subsubpostdn = "UPDATE $wpdb->posts SET menu_order='$pageorder' WHERE ID='$subsubpagedn'";
			$wpdb->query($subsubpostdn); #updated db
			}
		}
	
	}
	if($wp_version >= 2.1){
		$pageposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'page' AND post_parent = '0' ORDER BY menu_order");
	}else{
		$pageposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_status = 'static' AND post_parent = '0' ORDER BY menu_order");
	}
		
		?>
		<div class="wrap">
		<h2>ShiftThis Order Pages</h2>
		
		<form method="post" action="<?=$_SERVER['REQUEST_URI']?>&amp;update=true">
		<ul style="list-style:none">
		<?php		
		foreach ($pageposts as $page){ ?>
			<li><?=$page->menu_order;?>. <input type="hidden" name="page_<?=$page->ID;?>" value="<?=$page->menu_order;?>" />
			<input type="submit" name="plus" value="+" onclick="this.value='<?=$page->ID;?>'" />
			<input type="submit" name="minus" value="-" onclick="this.value='<?=$page->ID;?>'" />
			 <strong><?=$page->post_title;?></strong> [ <a href="<?=get_settings('siteurl').'/wp-admin/post.php?action=edit&post='.$page->ID; ?>" title="Edit This Page" target="_blank">edit</a> ]
			<?php
			
				if($wp_version >= 2.1){
		$subpageposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'page' AND post_parent = '$page->ID' ORDER BY menu_order");
	}else{
		$subpageposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_status = 'static' AND post_parent = '$page->ID' ORDER BY menu_order");
	}
				
				if ($subpageposts == true){
				?>
				<ul style="list-style:none">
					<?php foreach($subpageposts as $subpage){ ?>
					<li><?=$subpage->menu_order;?>. <input type="hidden" name="subpage_<?=$subpage->ID;?>" value="<?=$subpage->menu_order;?>" />
					<input type="submit" name="subplus" value="+" onclick="this.value='<?=$subpage->ID;?>'" />
			<input type="submit" name="subminus" value="-" onclick="this.value='<?=$subpage->ID;?>'" />
			 <strong><?=$subpage->post_title;?></strong> [ <a href="<?=get_settings('siteurl').'/wp-admin/post.php?action=edit&post='.$subpage->ID; ?>" title="Edit This Page" target="_blank">edit</a> ]
			 
			 <?php
			 if($wp_version >= 2.1){
		$subsubpageposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'page' AND post_parent = '$subpage->ID' ORDER BY menu_order");
	}else{
		$subsubpageposts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_status = 'static' AND post_parent = '$subpage->ID' ORDER BY menu_order");
	}
				
				if ($subsubpageposts == true){
			 ?>
				<ul style="list-style:none">
					<?php foreach($subsubpageposts as $subsubpage){ ?>
					<li><?=$subsubpage->menu_order;?>. <input type="hidden" name="subsubpage_<?=$subsubpage->ID;?>" value="<?=$subsubpage->menu_order;?>" />
					<input type="submit" name="subsubplus" value="+" onclick="this.value='<?=$subsubpage->ID;?>'" />
			<input type="submit" name="subsubminus" value="-" onclick="this.value='<?=$subsubpage->ID;?>'" />
			 <strong><?=$subsubpage->post_title;?></strong> [ <a href="<?=get_settings('siteurl').'/wp-admin/post.php?action=edit&post='.$subsubpage->ID; ?>" title="Edit This Page" target="_blank">edit</a> ]</li>
					<?php } ?>
				</ul>
			 </li>
					<?php } ?>
			 
			 </li>
					<?php } ?>
				</ul>
				<?php } ?>
			</li>
			<?php 
		}
		?>
		</ul>
		</form>
		</div>
		<div class="wrap">
	
		<h2>Instructions</h2>
		<p>Use the <input type="button" value="+" /> &amp;
			<input type="button" value="-" />
		buttons to move a page up or down in pageorder.  The actual pageorder value appears to the left.<br />
<small><strong>Note:</strong> This plugin only supports a maximum of 2 levels of Page Children (ie. SubSubPages)</small></p>
	    <p>In order to make use of this plugin you will need to use the <strong>wp_list_pages()</strong> function with the parameter of <strong>sort_column=menu_order</strong> included in your template.  For example:</p>
		<code style="display:block; border:solid 2px #CCCCCC; padding:5px; margin=10px;">	
		<strong>&lt;?php</strong> wp_list_pages('sort_column=menu_order' ); <strong>?&gt;</strong>		</code>	<p>For more information please see the <a href="http://docs.shiftthis.net/wordpress_order_pages_plugin">Documentation</a> or ask a question in the <a href="http://support.shiftthis.net">ShiftThis Support Forum</a>.  More information on the wp_list_pages() function is available in the <a href="http://codex.wordpress.org/Template_Tags/wp_list_pages">Wordpress Codex</a>. 	</p>
	</div>
		<?php
}
function st_po_add_pages(){
	global $minlevel;
	add_management_page('ShiftThis | Order Pages', 'Order Pages', $minlevel, __FILE__, 'st_pageorder');
}
add_action('admin_menu', 'st_po_add_pages');
?>
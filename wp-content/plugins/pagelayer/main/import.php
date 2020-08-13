<?php

//////////////////////////////////////////////////////////////
//===========================================================
// template_import.php
//===========================================================
// PAGELAYER
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:	   23rd Jan 2017
// Time:	   23:00 hrs
// Site:	   http://pagelayer.com/wordpress (PAGELAYER)
// ----------------------------------------------------------
// Please Read the Terms of use at http://pagelayer.com/tos
// ----------------------------------------------------------
//===========================================================
// (c)Pagelayer Team
//===========================================================
//////////////////////////////////////////////////////////////

// Are we being accessed directly ?
if(!defined('PAGELAYER_VERSION')) {
	exit('Hacking Attempt !');
}

include_once(PAGELAYER_DIR.'/main/settings.php');

function pagelayer_import(){
	
	global $pagelayer, $pagelayer_theme, $pagelayer_theme_url, $pagelayer_theme_path, $pagelayer_pages, $pl_error;
		
	$pagelayer_theme = wp_get_theme();
	$pagelayer_theme_url = get_stylesheet_directory_uri();
	$pagelayer_theme_path = get_stylesheet_directory();
	
	// Get the pages
	$pagelayer_templates = @json_decode(file_get_contents($pagelayer_theme_path.'/pagelayer.conf'), true);
	$pagelayer_pages = @json_decode(file_get_contents($pagelayer_theme_path.'/pagelayer-data.conf'), true);
	
	if(isset($_POST['theme'])){
		$GLOBALS['pl_saved'] = pagelayer_import_theme($pagelayer_theme);
	}
	
	// Have we already imported ?
	$imported = get_option('pagelayer_theme_'.get_template().'_imported');
	if(!empty($imported)){
		$GLOBALS['pl_warn'] = __('You have already imported the content of this theme. You can re-import the same by either choosing to over-write existing pages / pagelayer templates OR creating duplicate content !', 'pagelayer');
	}
	
	// Call the theme
	pagelayer_import_T();
	
}

function pagelayer_import_T(){
	
	global $pagelayer, $pagelayer_theme, $pagelayer_theme_url, $pagelayer_theme_path, $pagelayer_pages, $pl_error;
	
	pagelayer_page_header('Pagelayer - Import Template');
	
	// Any errors ?
	if(!empty($pl_error)){
		pagelayer_report_error($pl_error);echo '<br />';
	}

	// Saved ?
	if(!empty($GLOBALS['pl_saved'])){
		echo '<div class="notice notice-success"><p>'. __('The theme content was successfully imported', 'pagelayer'). '</p></div>';

	// Warn ?
	}elseif(!empty($GLOBALS['pl_warn'])){
		echo '<div class="notice notice-warning"><p>'.$GLOBALS['pl_warn'].'</p></div>';
	}
	
	// Is it a pagelayer theme ?
	if(!file_exists($pagelayer_theme_path.'/pagelayer.conf')){
		echo 'This utility is for importing content of the current active theme if its a Pagelayer Theme. Your current theme is <b>not</b> a Pagelayer exported theme ! If you want to export your content and make it into a distributable theme, please refer to the guide <a href="">here</a>.';
		die();
	}
	
	echo '
<style>
.pagelayer_img_screen{
width: 120px;
margin: 0px 15px 10px 15px;
display: inline-block;
border: 1px solid transparent;
border-radius: 3px;
}

.pagelayer_img_selected{
border: 1px solid #1A9CDB;
}

.pagelayer_img_div{
overflow: hidden;
height: 160px;
}

.pagelayer_img_name{
text-align: center;
background: #fff;
padding: 5px 10px;
border-top: 1px solid #ccc;
}

.button-pagelayer{
padding: 12px 25px !important;
font-size: 15px !important;
font-weight: bold;
background: #7444fd !important;
color: #fff !important;
border: 1px solid #7444fd !important;
transition: all .3s linear;
pointer: cursor;
}

.button-pagelayer:hover{
background: #fff !important;
color: #7444fd !important;
}

/* The Modal (background) */
.pagelayer-modal {
display: none;
position: fixed;
z-index: 10000;
left: 0;
top: 0;
width: 100%;
height: 100%;
overflow: auto;
background-color: rgb(0,0,0);
background-color: rgba(0,0,0,0.4);
}

/* Modal Content/Box */
.pagelayer-modal-holder {
background-color: #fefefe;
margin: 15% auto; /* 15% from the top and centered */
border: 1px solid #888;
width: 50%;
min-height: 200px;
position: relative;
}

/* The Close Button */
.pagelayer-modal-close {
color: #aaa;
float: right;
font-size: 28px;
font-weight: bold;
}

.pagelayer-modal-close:hover,
.pagelayer-modal-close:focus {
color: black;
text-decoration: none;
cursor: pointer;
}

.pagelayer-modal-header{
max-height: 80px;
top: 0px;
border-bottom: 1px solid #ccc;
}

.pagelayer-modal-footer{
max-height: 80px;
bottom: 0px;
border-top: 1px solid #ccc;
text-align: right;
}

.pagelayer-modal-header,
.pagelayer-modal-content,
.pagelayer-modal-footer{
padding: 15px;
width: 100%;
box-sizing: border-box;
}

#pagelayer-import-form>div{
padding: 4px;
font-weight: 600;
}

</style>

<!-- The Modal -->
<div id="pagelayerModal" class="pagelayer-modal">

	<!-- Modal holder -->
	<div class="pagelayer-modal-holder">

		<!-- Modal header -->
		<div class="pagelayer-modal-header">
			<b>Import Theme Contents</b> <span class="pagelayer-modal-close">&times;</span>
		</div>
		
		<!-- Modal content -->
		<div class="pagelayer-modal-content">		
			<form id="pagelayer-import-form" method="post" enctype="multipart/form-data">
				<input name="theme" value="'.get_template().'" type="hidden" />
				<div><input type="checkbox" name="delete_old_import" id="delete_old_import" /> Delete Previously Imported Content</div>
				<div><input type="checkbox" name="overwrite" /> Overwrite existing Pages with same name</div>
				<div><input type="checkbox" name="set_home_page" checked /> Set the Home Page as per the content</div>
			</form>
		</div>
		
		<!-- Modal footer -->
		<div class="pagelayer-modal-footer">
			<button class="button button-primary" onclick="jQuery(\'#pagelayer-import-form\').submit()">Import</button> &nbsp;
			<button class="button pagelayer-cancel">Cancel</button>
		</div>
	</div>

</div> 

<script>

function pagelayer_modal(sel){
	
	var modal = jQuery(sel);
	
	modal.show();

	// Get the <span> element that closes the modal
	var span = modal.find(".pagelayer-modal-close, .pagelayer-cancel");

	// When the user clicks on <span> (x), close the modal
	span.on("click", function() {
		modal.hide();
	});

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if(event.target == modal[0]){
			modal.hide();
		}
	}
}

jQuery(document).ready(function(){
	var $ = jQuery;

	var choose_image = function(jEle){		
		$("#pagelayer_display_image").attr("src", jEle.find("img").attr("src"));
		
		$(".pagelayer_img_screen").removeClass("pagelayer_img_selected");
		jEle.addClass("pagelayer_img_selected");
	}
	
	var first = $(".pagelayer_img_screen:first");
	var home = $(".pagelayer_img_screen[page=home]");
	
	if(home.length > 0){
		first = home;
	}
	
	choose_image(first);
	
	$(".pagelayer_img_screen").on("click", function(){
		choose_image($(this));
	});
	
	$("#pagelayer-import-form").on("submit", function(){
		
		if(!jQuery("#delete_old_import").is(":checked")){
			return true;
		}
		
		if(confirm("This will delete any pages / pagelayer templates imported earlier. Should we proceed ?")){
			return true;
		}else{
			return false;
		}
		
	});
	
});
</script>

<div><h1 style="margin-bottom: 10px; padding-top: 0px;">'.$pagelayer_theme->name.'</h1></div>
<div style="margin: 0px -10px; vertical-align: top;">
	<div style="width: 52%; display: inline-block; text-align: center;">
		<div style="width: 100%; max-height: 400px; overflow: auto; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
			<img id="pagelayer_display_image" src="'.$pagelayer_theme_url.'/screenshots/home.jpg" width="100%">
		</div>
	</div>
	<div style="width: 45%; display: inline-block; padding: 0px 10px; vertical-align: top;">';
	
	foreach($pagelayer_pages['page'] as $k => $v){
		echo '<div class="pagelayer_img_screen" page="'.$k.'">
			<div class="pagelayer_img_div"><img src="'.$pagelayer_theme_url.'/screenshots/'.$k.'.jpg" width="100%" /></div>
			<div class="pagelayer_img_name">'.$v['post_title'].'</div>
		</div>';
	}
	
	echo '</div>
</div>

<div style="position:fixed; bottom: 30px; right: 30px;">
	<input name="import_theme" class="button button-pagelayer" value="Import Theme Content" type="button" onclick="pagelayer_modal(\'#pagelayerModal\')" />
</div>';
	
}

// The actual function to import the theme
function pagelayer_import_theme($new_theme){
	
global $wpdb, $wp_rewrite;	
global $pagelayer, $pagelayer_theme, $pagelayer_theme_url, $pagelayer_theme_path, $pagelayer_pages, $pl_error;
	
	$pagelayer_theme_path = get_stylesheet_directory();
	//die($pagelayer_theme_path);
	
	// Delete Old Data ?
	if(isset($_POST['delete_old_import'])){
		$args = array(
			'post_type' => ['page', 'post', $pagelayer->builder['name']],
			'meta_query' => array(
				array(
					'key' => 'pagelayer_imported_content',
					'compare' => 'EXISTS'
				)
			)
		);
		$query = new WP_Query($args);

		foreach ( $query->posts as $p ) {
			//echo $p->ID.'<br>';
			wp_delete_post($p->ID);
		}
	}
	
	/////////////////////////
	// Handle PAGELAYER DATA
	/////////////////////////
	
	// Load the PGL conf
	$pgl = file_get_contents($pagelayer_theme_path.'/pagelayer.conf');
	$pgl = @json_decode($pgl, true);
	
	if(empty($pgl['header'])){
		die('Header list not found. Report to Website Builder Team');
	}
	
	// Check the theme files
	foreach($pgl as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/'.$k.'.pgl');
		//print_r($path);
		
		// Does the page exist ?
		if(!file_exists($path) || pagelayer_cleanpath(realpath($path)) != $path){
			die('Something is fishy with this theme as the template '.$k.' of type '.$v['type'].' was not found');
		}
		
	}
	
	// Create the menu
	$menu_id = pagelayer_create_header_menu();
	
	// Check the theme files
	foreach($pgl as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/'.$k.'.pgl');
		
		$new_post = array();
	
		// Is the page there ?
		$template = get_page_by_path($k, OBJECT, $pagelayer->builder['name']);
		
		// It does exist so save the revision IF its the header and footer
		if(!empty($template)){
			
			$rev = wp_save_post_revision($template->ID);
			
			// Did we save the rev ?
			if(empty($rev)){
				// TODO : Throw error
			}
			
			$new_post['ID'] = $template->ID;
			
		}
		
		// Make an array
		$new_post['post_content'] = file_get_contents($path);
		$new_post['post_title'] = $v['title'];
		$new_post['post_name'] = $k;
		$new_post['post_type'] = $pagelayer->builder['name'];
		$new_post['post_status'] = 'publish';
		$new_post['comment_status'] = 'closed';
		$new_post['ping_status'] = 'closed';		
		//r_print($new_post);die();
			
		// Lets replace the menu we created
		if(!is_wp_error($menu_id)){
			$new_post['post_content'] = preg_replace('/\[pl_wp_menu ([^\]]*)nav_list="(\d*)"([^\]]*)\]/is', '[pl_wp_menu ${1}nav_list="'.$menu_id.'"${3}]', $new_post['post_content']);
		}
		
		// Now insert / update the post
		$ret = pagelayer_insert_content($new_post, $err);
		$post_id = $ret;
		
		// Did we save the rev ?
		if(empty($ret)){
			die('Could not update the Pagelayer Template '.$k);
		}
		
		// Save our template type
		update_post_meta($post_id, 'pagelayer_template_type', $v['type']);
		update_post_meta($post_id, 'pagelayer_template_conditions', $v['conditions']);
		update_post_meta($post_id, 'pagelayer_imported_content', $new_theme->template);
		
		// Any conditions having Page IDs that need to be updated ?
		if(!empty($v['conditions'])){
			
			foreach($v['conditions'] as $ck => $cv){
				if(!empty($cv['id'])){
					$conditions[$post_id][$ck] = $cv['id'];
				}
			}
			
		}
		
	}
	
	/////////////////////////
	// Handle the PAGES Data
	/////////////////////////
	
	// Load the new themes pages array
	$data = file_get_contents($pagelayer_theme_path.'/pagelayer-data.conf');
	$data = @json_decode($data, true);
	//r_print($data);die();
	
	if(empty($data['page'])){
		die('Pages list not found. Report to Website Builder Team');
	}
	
	// Check the theme files
	foreach($data['page'] as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/data/page/'.$k);
		
		// Does it have the title and slug ?
		if(empty($v['post_title']) || empty($v['post_name'])){
			die('Something is fishy with this theme as there is no title or slug for '.$k);
		}
		
		// Does the page exist ?
		if(!file_exists($path) || pagelayer_cleanpath(realpath($path)) != $path){
			die('Something is fishy with this theme');
		}
		
	}
	
	$menu_pages = [];
	
	// Now check the pages if it exist in this installation ?
	foreach($data['page'] as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/data/page/'.$k);
		
		// Is the page there ?
		$page = get_page_by_path($v['post_name']);
		//r_print($page);
			
		$new_post = array();
		
		// It does exist so save the revision IF its the header and footer
		if(!empty($page) && isset($_POST['overwrite'])){
			
			$rev = wp_save_post_revision($page->ID);
			
			$new_post['ID'] = $page->ID;
			
		}
			
		// Make an array
		$new_post['post_content'] = file_get_contents($path);
		$new_post['post_title'] = $v['post_title'];
		$new_post['post_name'] = $v['post_name'];
		$new_post['post_type'] = 'page';
		$new_post['post_status'] = 'publish';			
		//r_print($new_post);die();
		
		// Lets replace the menu we created
		if(!is_wp_error($menu_id)){
			$new_post['post_content'] = preg_replace('/\[pl_wp_menu ([^\]]*)nav_list="(\d*)"([^\]]*)\]/is', '[pl_wp_menu ${1}nav_list="'.$menu_id.'"${3}]', $new_post['post_content']);
		}
		
		// Now insert / update the post
		$ret = pagelayer_insert_content($new_post, $err);
		
		// Did we save the post ?
		if(empty($ret)){
			die('Could not update the page '.$v['post_name']);
		}
		
		update_post_meta($ret, 'pagelayer_imported_content', $new_theme->template);
		
		$pages_id_map[$v['ID']] = $ret;
		
		// Skip Header, Footer and Home pages
		if(preg_match('/^home/is', $new_post['post_name'])){
			$home_page = $ret;
		}
		
	}
	
	// Update Post for import
	if(!empty($conditions)){
		
		foreach($conditions as $post_ID => $v){
			
			$cond = get_post_meta($post_ID, 'pagelayer_template_conditions', 1);
			
			foreach($v as $ck => $cv){
			
				if(!empty($pages_id_map[$cv])){
					$cond[$ck]['id'] = $pages_id_map[$cv];
				}
			
			}
			
			update_post_meta($post_id, 'pagelayer_template_conditions', $cond);
			
		}
		
	}
	
	// Save that we have imported the theme
	update_option('pagelayer_theme_'.get_template().'_imported', time(), true);
	
	// Call a function for the theme if they want to execute something
	$ret = apply_filters('pagelayer_theme_imported', get_template());
	
	if(isset($_POST['set_home_page'])){
		
		// Get the home page ID
		$blog = get_page_by_path('blog');
		
		// Insert the blog page
		if(empty($blog)){
			
			$new_post['post_content'] = '';
			$new_post['post_title'] = 'Blog';
			$new_post['post_name'] = 'blog';
			$new_post['post_type'] = 'page';
			$new_post['post_status'] = 'publish';
		
			// Now insert / update the post
			$blog_id = wp_insert_post($new_post);
			
		}else{
			$blog_id = $blog->ID;
		}
		
		// Set the blog page
		update_option('page_for_posts', $blog_id);
		
		// Set the blog page
		update_option('show_on_front', 'page');
		
		// Set home page as the default page
		if(!empty($home_page)){
			update_option('page_on_front', $home_page);
		}
		
	}
	
	// Update the menu
	pagelayer_update_header_menu($menu_id, $pages_id_map);
	
	return true;

}

// Create the menu
function pagelayer_create_header_menu(){
		
	// Create the menu if not exists
	$menu_name = 'PFX Header Menu';
	$menu_exists = wp_get_nav_menu_object($menu_name);
	
	// If there is no menu we will need to add it
	if(!empty($menu_exists)){
		wp_delete_nav_menu($menu_exists);
	}
	
	// Insert the Menu
	$menu_id = wp_create_nav_menu($menu_name);
	
	return $menu_id;

}

// Update the header menu
function pagelayer_update_header_menu($menu_id, $pages){
	
	$menu_pages = [];
	
	$home = get_option('page_on_front');
	if(!empty($home)){
		$menu_pages[] = $home;
	}
	
	$blog = get_option('page_for_posts');
	if(!empty($blog)){
		$menu_pages[] = $blog;
	}
	
	// The other links
	foreach($pages as $pk => $pv){
		
		// Skip Header, Footer and Home pages
		if(in_array($pv, $menu_pages)){
			continue;
		}
		
		$menu_pages[] = $pv;
		
	}
	
	// Get the pages
	foreach($menu_pages as $pk => $page_id){
		$menu_pages[$pk] = get_post($page_id);
	}
	
	// The other links
	foreach($menu_pages as $pk => $pv){
		
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  $pv->post_title,
			'menu-item-url' => home_url( '/'.$pv->post_name.'/' ),
			'menu-item-status' => 'publish',
			'menu-item-type' => 'post_type',
			'menu-item-object' => 'page',
			'menu-item-object-id' => $pv->ID));
		
	}
	
	// We need to enable auto add new pages
	$options = (array) get_option('nav_menu_options');
	
	if (!isset($options['auto_add'])){
		$options['auto_add'] = array();
	}
	
	$options['auto_add'][] = $menu_id;
	update_option('nav_menu_options', $options);
	
}
<?php

$popularfx_api_url = 'https://s2.softaculous.com/a/popularfx/';

if(!function_exists('popularfx_get_current_theme_slug')){
	
function popularfx_cleanpath($path){
	$path = str_replace('\\', '/', $path);
	$path = str_replace('//', '/', $path);
	return rtrim($path, '/');
}
}

if(!function_exists('popularfx_get_current_theme_slug')){

// Return the name of the current theme folder
function popularfx_get_current_theme_slug(){
	
	$theme_root = popularfx_cleanpath(get_theme_root());	
	$debug = debug_backtrace();
	$caller = popularfx_cleanpath($debug[0]['file']);
	
	$left = str_ireplace($theme_root.'/', '', $caller);
	$val = explode('/', $left);
	return $val[0];
}

}

if(!function_exists('popularfx_check_for_update')){

function popularfx_check_for_update($slug, $checked_data){
	
	global $wp_version, $popularfx_api_url;
	
	$theme_data = wp_get_theme($slug);
	//print_r($theme_data);die();
	$cur_version = $theme_data->get('Version');
	
	// Start checking for an update
	$send_for_check = array(
		'timeout' => 90,
		'user-agent' => 'WordPress'		
	);
	
	$raw_response = wp_remote_post( $popularfx_api_url.'/update.php?softname='.$slug, $send_for_check );
	//echo"<pre>";print_r($raw_response);echo "</pre>";die();
	
	// Is the response valid ?
	if ( !is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) ){		
		$response = json_decode($raw_response['body'], true);
	}	
	//echo"<pre>";print_r($response);echo "</pre>";die();
	
	// Feed the update data into WP updater
	if(!empty($response[$slug]['version']) && version_compare($cur_version, $response[$slug]['version'], '<')){
		$checked_data->response[$slug]['new_version'] = $response[$slug]['version'];
		$checked_data->response[$slug]['package'] = $response[$slug]['download_url'];
		$checked_data->response[$slug]['url'] = $response[$slug]['www_url'];
	}	
	//echo"<pre>";print_r($checked_data);echo "</pre>";die();
	
	return $checked_data;
}

}

if(!function_exists('popularfx_show_promo')){

function popularfx_show_promo(){
	
	global $popularfx;
	
	$slug = popularfx_get_current_theme_slug();		
	$opts = $popularfx['promo'][$slug];
	$theme_data = wp_get_theme($slug);
	
	echo '
<style>
.pfx_button {
background-color: #4CAF50; /* Green */
border: none;
color: white;
padding: 8px 16px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 16px;
margin: 4px 2px;
-webkit-transition-duration: 0.4s; /* Safari */
transition-duration: 0.4s;
cursor: pointer;
}

.pfx_button:focus{
border: none;
color: white;
}

.pfx_button1 {
color: white;
background-color: #4CAF50;
border:3px solid #4CAF50;
}

.pfx_button1:hover {
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
border:3px solid #4CAF50;
}

.pfx_button2 {
color: white;
background-color: #0085ba;
}

.pfx_button2:hover {
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}

.pfx_button3 {
color: white;
background-color: #365899;
}

.pfx_button3:hover {
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}

.pfx_button4 {
color: white;
background-color: rgb(66, 184, 221);
}

.pfx_button4:hover {
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}

.popularfx_promo-close{
float:right;
text-decoration:none;
margin: 5px 10px 0px 0px;
}

.popularfx_promo-close:hover{
color: red;
}
</style>	

<script>
jQuery(document).ready( function() {
	(function($) {
		$("#popularfx_promo .popularfx_promo-close").click(function(){
			var data;
			
			// Hide it
			$("#popularfx_promo").hide();
			
			// Save this preference
			$.post("'.admin_url('?'.$slug.'_theme_promo=0').'", data, function(response) {
				//alert(response);
			});
		});
	})(jQuery);
});
</script>

<div class="notice notice-success" id="popularfx_promo" style="min-height:120px">
	<a class="popularfx_promo-close" href="javascript:" aria-label="Dismiss this Notice">
		<span class="dashicons dashicons-dismiss"></span> Dismiss
	</a>';
	
	if(!empty($opts['image'])){
		echo '<img src="'.$opts['image'].'" style="float:left; margin:10px 20px 10px 10px" width="100" />';
	}
	
	echo '
	<p style="font-size:16px">'.(empty($opts['msg']) ? 'We are glad you like '.$theme_data->get('Name').' and have been using it since the past few days. It is time to take the next step !' : $opts['msg']).'</p>
	<p>
		'.(empty($opts['pro_url']) ? '' : '<a class="pfx_button pfx_button1" target="_blank" href="'.$opts['pro_url'].'">Upgrade to Pro</a>').'
		'.(empty($opts['rating']) ? '' : '<a class="pfx_button pfx_button2" target="_blank" href="'.$opts['rating'].'">Rate it 5★\'s</a>').'
		'.(empty($opts['facebook']) ? '' : '<a class="pfx_button pfx_button3" target="_blank" href="'.$opts['facebook'].'"><span class="dashicons dashicons-thumbs-up"></span> Facebook</a>').'
		'.(empty($opts['twitter']) ? '' : '<a class="pfx_button pfx_button4" target="_blank" href="'.$opts['twitter'].'"><span class="dashicons dashicons-twitter"></span> Tweet</a>').'
		'.(empty($opts['website']) ? '' : '<a class="pfx_button pfx_button4" target="_blank" href="'.$opts['website'].'">Visit our website</a>').'
	</p>
</div>';

}

}

// Are we to show a promo ?
if(!function_exists('popularfx_maybe_promo')){

function popularfx_maybe_promo($opts){
	
	global $popularfx;
	
	$slug = popularfx_get_current_theme_slug();	
	
	// There must be an interval
	if(empty($opts['interval'])){
		return false;
	}
	
	// Are we to show the theme promo	
	$opt_name = $slug.'_promo_time';
	$promo_time = get_option($opt_name);
	
	// First time access
	if(empty($promo_time)){
		update_option($opt_name, time() + (!empty($opts['after']) ? $opts['after'] * 86400 : 0));
		$promo_time = get_option($opt_name);
	}
	
	// Is there interval elapsed
	if(time() > $promo_time){
		$popularfx['promo'][$slug] = $opts;
		add_action('admin_notices', 'popularfx_show_promo');
	}
	
	// Are we to disable the promo
	if(isset($_GET[$slug.'_theme_promo']) && (int)$_GET[$slug.'_theme_promo'] == 0){
		update_option($opt_name, time() + ($opts['interval'] * 86400));
		die('DONE');
	}
	
}

}

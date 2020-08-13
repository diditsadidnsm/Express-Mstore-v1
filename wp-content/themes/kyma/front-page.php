<?php
$kyma_theme_options = kyma_theme_options();
if ($kyma_theme_options['_frontpage']=="1" && is_front_page())
{	get_header();
	get_template_part('home', 'slider'); 
	foreach($kyma_theme_options['home_sections'] as $section){
		get_template_part('home',$section);
	}
	get_footer();
} else 
{	
	if(is_page()){
		if(is_page_template('page-left.php')){
			get_template_part('page-left');
		}else if(is_page_template('page-fullwidth.php')){
			get_template_part('page-fullwidth');
		}else{	
			get_template_part('page');
		}
	}else{
		get_template_part('index');
	}
}
?>
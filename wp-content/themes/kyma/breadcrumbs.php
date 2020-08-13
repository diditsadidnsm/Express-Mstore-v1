<?php $kyma_theme_options = kyma_theme_options();
if( $kyma_theme_options['crumb_and_title']=="not_of_them" ) return; 
?>
<section class="content_section page_title">
    <div class="content clearfix">
        <h1 class=""><?php single_post_title(); ?></h1>
        <?php 
		if( $kyma_theme_options['crumb_and_title'] == "allow_both" ){
			if (function_exists('kyma_breadcrumbs')) kyma_breadcrumbs(); 
		}
		?>
    </div>
</section>
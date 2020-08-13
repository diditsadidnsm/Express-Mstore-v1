<?php

/**
 * @param array  $help_text
 * @param string $module
 * @return array
 */
function aioseop_pro_video_sitemap_help_text( $help_text, $module ) {
	switch ( $module ) {
		case 'All_in_One_SEO_Pack':
			$help_text = aioseop_pro_help_text_general( $help_text );
			break;
		case 'All_in_One_SEO_Pack_Video_Sitemap':
			$help_text = aioseop_pro_help_text_video_sitemap( $help_text );
			break;
		default:
			break;
	}

	return $help_text;
}
add_filter( 'aioseop_helper_set_help_text', 'aioseop_pro_video_sitemap_help_text', 10, 2 );

/**
 * Help Text for General Settings
 *
 * @since 3.0
 *
 * @param array $help_text
 * @return array
 */
function aioseop_pro_help_text_general( $help_text ) {
	// General Settings.
	$help_text[ 'aiosp_license_key' ]             = __( 'This will be the license key received when the product was purchased. This is used for automatic upgrades.', 'all-in-one-seo-pack' );

	// Custom Post Type Settings.
	$help_text[ 'aiosp_taxactive' ]               = sprintf( __( 'Use these checkboxes to select which Taxonomies you want to use %s with.', 'all-in-one-seo-pack' ), AIOSEOP_PLUGIN_NAME );

	// Display Settings.
	$help_text[ 'aiosp_showseonews' ]             = __( 'This displays an SEO News widget on the dashboard.', 'all-in-one-seo-pack' );
	$help_text[ 'aiosp_admin_bar' ]               = sprintf( __( 'Check this to add %s to the Toolbar for easy access to your SEO settings.', 'all-in-one-seo-pack' ), AIOSEOP_PLUGIN_NAME );
	$help_text[ 'aiosp_custom_menu_order' ]       = sprintf( __( 'Check this to move the %s menu item to the top of your WordPress Dashboard menu.', 'all-in-one-seo-pack' ), AIOSEOP_PLUGIN_NAME );

	// Google Settings.
	$help_text[ 'aiosp_ga_track_outbound_forms' ] = __( 'Check this if you want to track outbound forms with Google Analytics.', 'all-in-one-seo-pack' );
	$help_text[ 'aiosp_ga_track_events' ]         = __( 'Check this if you want to track events with Google Analytics.', 'all-in-one-seo-pack' );
	$help_text[ 'aiosp_ga_track_url_changes' ]    = __( 'Check this if you want to track URL changes for single pages with Google Analytics.', 'all-in-one-seo-pack' );
	$help_text[ 'aiosp_ga_track_visibility' ]     = __( 'Check this if you want to track how long pages are in visible state with Google Analytics.', 'all-in-one-seo-pack' );
	/* translators: 'This option allows users to track media queries, allowing them to find out if users are viewing a responsive layout or not and which layout changes have been applied if the browser window has been resized by the user, see https://github.com/googleanalytics/autotrack/blob/master/docs/plugins/media-query-tracker.md. */
	$help_text[ 'aiosp_ga_track_media_query' ]    = __( 'Check this if you want to track media query matching and queries with Google Analytics.', 'all-in-one-seo-pack' );
	/* translators: The term "viewport" refers to the area of the page that is visible to the user, see https://www.w3schools.com/css/css_rwd_viewport.asp. */
	$help_text[ 'aiosp_ga_track_impressions' ]    = __( 'Check this if you want to track when elements are visible within the viewport with Google Analytics.', 'all-in-one-seo-pack' );
	$help_text[ 'aiosp_ga_track_scroller' ]       = __( 'Check this if you want to track how far down a user scrolls a page with Google Analytics.', 'all-in-one-seo-pack' );
	$help_text[ 'aiosp_ga_track_social' ]         = __( 'Check this if you want to track interactions with the official Facebook and Twitter widgets with Google Analytics.', 'all-in-one-seo-pack' );
	$help_text[ 'aiosp_ga_track_clean_url' ]      = __( 'Check this if you want to ensure consistency in URL paths reported to Google Analytics.', 'all-in-one-seo-pack' );
	$help_text[ 'aiosp_gtm_container_id' ]        = __( 'Enter your Google Tag Manager Container ID to deploy your marketing tag in the source code. Ignore this setting if you are not familiar with Google Tag Manager.', 'all-in-one-seo-pack' );

	$help_doc_link = array(
		// General Settings.
		'aiosp_license_key'                 => 'https://semperplugins.com/documentation/general-settings/#license-key',

		// Custom Post Type Settings.
		'aiosp_taxactive'                   => 'https://semperplugins.com/documentation/custom-post-type-settings/#seo-on-only-these-taxonomies',

		// Display Settings.
		'aiosp_showseonews'                 => 'https://semperplugins.com/documentation/display-settings/#show-seo-news',
		'aiosp_admin_bar'                   => 'https://semperplugins.com/documentation/display-settings/#display-menu-in-admin-bar',
		'aiosp_custom_menu_order'           => 'https://semperplugins.com/documentation/display-settings/#display-menu-at-the-top',

		// Google Settings.
		'aiosp_ga_track_outbound_forms'     => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
		'aiosp_ga_track_events'             => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
		'aiosp_ga_track_url_changes'        => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
		'aiosp_ga_track_visibility'         => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
		'aiosp_ga_track_media_query'        => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
		'aiosp_ga_track_impressions'        => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
		'aiosp_ga_track_scroller'           => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
		'aiosp_ga_track_social'             => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
		'aiosp_ga_track_clean_url'          => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
		'aiosp_gtm_container_id'            => 'https://semperplugins.com/documentation/advanced-google-analytics-settings/',
	);

	$taxonomies = get_taxonomies( '', 'names' );
	$remove_tax = array( 'nav_menu', 'link_category', 'post_format', 'category', 'post_tag' );
	$taxonomies = array_diff( $taxonomies, $remove_tax );
	foreach ( $taxonomies as $taxonomy ) {
		if ( ! isset( $help_text[ 'aiosp_' . $taxonomy . 'tax_title_format' ] ) ) {

			$help_text_macros =
				'<dt>%site_title%</dt>' .
				'<dd>' . __( 'Your site title', 'all-in-one-seo-pack' ) . '</dd>' .
				'<dt>%site_description%</dt>' .
				'<dd>' . __( 'Your site description', 'all-in-one-seo-pack' ) . '</dd>' .
				'<dt>%taxonomy_title%</dt>' .
				'<dd>' . sprintf( __( 'The original title of the %s', 'all-in-one-seo-pack' ), __( 'Taxonomy', 'all-in-one-seo-pack' ) ) . '</dd>' .
				'<dt>%taxonomy_description%</dt>' .
				'<dd>' . sprintf( __( 'The description of the %s', 'all-in-one-seo-pack' ), __( 'Taxonomy', 'all-in-one-seo-pack' ) ) . '</dd>';

			$help_text[ 'aiosp_' . $taxonomy . '_tax_title_format' ] = __( 'The following macros are supported:', 'all-in-one-seo-pack' ) . '<dl>' . $help_text_macros . '</dl>' . '<br /><a href="https://semperplugins.com/documentation/custom-post-type-settings/#custom-titles" target="_blank">' . __( 'Click here for documentation on this setting', 'all-in-one-seo-pack' ) . '</a>';
		}
	}

	foreach ( $help_doc_link as $k1_slug => $v1_url ) {
		$help_text[ $k1_slug ] .= '<br /><br /><a href="' . $v1_url . '" target="_blank">' . __( 'Click here for documentation on this setting.', 'all-in-one-seo-pack' ) . '</a>';
	}

	return $help_text;
}

/**
 * Help Text for Video Sitemap
 *
 * @since 3.0
 *
 * @param array $help_text
 * @return array
 */
function aioseop_pro_help_text_video_sitemap( $help_text ) {
	$rtn_help_text = array(
		// Video Sitemap settings.
		'aiosp_video_sitemap_rss_sitemap'     => __( 'Create RSS Sitemap as well.', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_daily_cron'      => __( 'Notify search engines based on the selected schedule, and also update static sitemap daily if in use. (this uses WP-Cron, so make sure this is working properly on your server as well)', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_indexes'         => __( 'Organize sitemap entries into distinct files in your sitemap. We recommend you enable this setting if your sitemap contains more than 1,000 URLs.', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_max_posts'       => __( 'Allows you to specify the maximum number of posts in a sitemap (up to 50,000).', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_posttypes'       => __( 'Select which Post Types appear in your sitemap.', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_taxonomies'      => __( 'Select which taxonomy archives appear in your sitemap', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_author'          => __( 'Include Author Archives in your sitemap.', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_images'          => __( 'Exclude Images in your sitemap.', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_rewrite'         => __( 'Dynamically creates the XML sitemap instead of using a static file.', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_custom_fields'   => __( 'Enable this option to look for videos in custom fields as well.', 'all-in-one-seo-pack' ),

		// Additional Pages.
		'aiosp_video_sitemap_addl_url'        => __( 'URL to the page. This field accepts relative URLs or absolute URLs with the protocol specified.', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_addl_prio'       => __( 'The priority of the page.', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_addl_freq'       => __( 'The frequency of the page.', 'all-in-one-seo-pack' ),
		'aiosp_video_sitemap_addl_mod'        => __( 'Last modified date of the page.', 'all-in-one-seo-pack' ),

		// Excluded Items.
		'aiosp_video_sitemap_excl_pages'      => __( 'Use page slugs or page IDs, separated by commas, to exclude pages from the sitemap.', 'all-in-one-seo-pack' ),

		// Priorities.
		'aiosp_video_sitemap_prio_homepage'       => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'priority', 'all-in-one-seo-pack' ), __( 'Homepage', 'all-in-one-seo-pack' ) ),
		'aiosp_video_sitemap_prio_post'           => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'priority', 'all-in-one-seo-pack' ), __( 'Post', 'all-in-one-seo-pack' ) ),
		'aiosp_video_sitemap_prio_taxonomies'     => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'priority', 'all-in-one-seo-pack' ), __( 'Taxonomies', 'all-in-one-seo-pack' ) ),
		'aiosp_video_sitemap_prio_archive'        => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'priority', 'all-in-one-seo-pack' ), __( 'Archive Pages', 'all-in-one-seo-pack' ) ),
		'aiosp_video_sitemap_prio_author'         => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'priority', 'all-in-one-seo-pack' ), __( 'Author Pages', 'all-in-one-seo-pack' ) ),

		// Frequencies.
		'aiosp_video_sitemap_freq_homepage'       => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'frequency', 'all-in-one-seo-pack' ), __( 'Homepage', 'all-in-one-seo-pack' ) ),
		'aiosp_video_sitemap_freq_post'           => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'frequency', 'all-in-one-seo-pack' ), __( 'Post', 'all-in-one-seo-pack' ) ),
		'aiosp_video_sitemap_freq_taxonomies'     => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'frequency', 'all-in-one-seo-pack' ), __( 'Taxonomies', 'all-in-one-seo-pack' ) ),
		'aiosp_video_sitemap_freq_archive'        => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'frequency', 'all-in-one-seo-pack' ), __( 'Archive Pages', 'all-in-one-seo-pack' ) ),
		'aiosp_video_sitemap_freq_author'         => sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'frequency', 'all-in-one-seo-pack' ), __( 'Author Pages', 'all-in-one-seo-pack' ) ),
	);

	$help_doc_link = array(
		// XML Sitemap.
		'aiosp_video_sitemap_rss_sitemap'     => 'https://semperplugins.com/documentation/xml-sitemaps-module/#create-rss_sitemap',
		'aiosp_video_sitemap_daily_cron'      => 'https://semperplugins.com/documentation/xml-sitemaps-module/#schedule-updates',
		'aiosp_video_sitemap_indexes'         => 'https://semperplugins.com/documentation/xml-sitemaps-module/#enable-sitemap-indexes',
		'aiosp_video_sitemap_max_posts'       => 'https://semperplugins.com/documentation/xml-sitemaps-module/#enable-sitemap-indexes',
		'aiosp_video_sitemap_posttypes'       => 'https://semperplugins.com/documentation/xml-sitemaps-module/#post-types-and-taxonomies',
		'aiosp_video_sitemap_taxonomies'      => 'https://semperplugins.com/documentation/xml-sitemaps-module/#post-types-and-taxonomies',
		'aiosp_video_sitemap_author'          => 'https://semperplugins.com/documentation/xml-sitemaps-module/#include-archive-pages',
		'aiosp_video_sitemap_images'          => 'https://semperplugins.com/documentation/xml-sitemaps-module/#exclude-images',
		'aiosp_video_sitemap_rewrite'         => 'https://semperplugins.com/documentation/xml-sitemaps-module/#dynamically-generate-sitemap',
		'aiosp_video_sitemap_custom_fields'   => 'https://semperplugins.com/documentation/video-sitemap/#custom-fields-video-sitemap',

		// Additional Pages.
		'aiosp_video_sitemap_addl_url'        => 'https://semperplugins.com/documentation/xml-sitemaps-module/#additional-pages',
		'aiosp_video_sitemap_addl_prio'       => 'https://semperplugins.com/documentation/xml-sitemaps-module/#additional-pages',
		'aiosp_video_sitemap_addl_freq'       => 'https://semperplugins.com/documentation/xml-sitemaps-module/#additional-pages',
		'aiosp_video_sitemap_addl_mod'        => 'https://semperplugins.com/documentation/xml-sitemaps-module/#additional-pages',

		// Exclude Items.
		'aiosp_video_sitemap_excl_pages'      => 'https://semperplugins.com/documentation/xml-sitemaps-module/#excluded-items',

		// Priorities.
		'aiosp_video_sitemap_prio_homepage'   => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',
		'aiosp_video_sitemap_prio_post'       => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',
		'aiosp_video_sitemap_prio_taxonomies' => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',
		'aiosp_video_sitemap_prio_archive'    => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',
		'aiosp_video_sitemap_prio_author'     => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',

		// Frequencies.
		'aiosp_video_sitemap_freq_homepage'   => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',
		'aiosp_video_sitemap_freq_post'       => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',
		'aiosp_video_sitemap_freq_taxonomies' => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',
		'aiosp_video_sitemap_freq_archive'    => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',
		'aiosp_video_sitemap_freq_author'     => 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies',

	);

	$args = array(
		'public' => true,
	);

	$post_types = get_post_types( $args, 'names' );
	foreach ( $post_types as $pt ) {
		$pt_obj = get_post_type_object( $pt );
		$rtn_help_text[ 'aiosp_video_sitemap_prio_post_' . $pt ] = sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'priority', 'all-in-one-seo-pack' ), ucwords( $pt_obj->label ) );
		$rtn_help_text[ 'aiosp_video_sitemap_freq_post_' . $pt ] = sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'frequency', 'all-in-one-seo-pack' ), ucwords( $pt_obj->label ) );
		$help_doc_link[ 'aiosp_video_sitemap_prio_post_' . $pt ] = 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies';
		$help_doc_link[ 'aiosp_video_sitemap_freq_post_' . $pt ] = 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies';
	}

	$taxonomies = get_taxonomies( $args, 'object' );
	foreach ( $taxonomies as $tax ) {
		$rtn_help_text[ 'aiosp_video_sitemap_prio_taxonomies_' . $tax->name ] = sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'priority', 'all-in-one-seo-pack' ), ucwords( $tax->label ) );
		$rtn_help_text[ 'aiosp_video_sitemap_freq_taxonomies_' . $tax->name ] = sprintf( __( 'Manually set the %1$s of your %2$s.', 'all-in-one-seo-pack' ), __( 'frequency', 'all-in-one-seo-pack' ), ucwords( $tax->label ) );
		$help_doc_link[ 'aiosp_video_sitemap_prio_taxonomies_' . $tax->name ] = 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies';
		$help_doc_link[ 'aiosp_video_sitemap_freq_taxonomies_' . $tax->name ] = 'https://semperplugins.com/documentation/xml-sitemaps-module/#priorities-and-frequencies';
	}

	foreach ( $help_doc_link as $k1_slug => $v1_url ) {
		$rtn_help_text[ $k1_slug ] .= '<br /><br /><a href="' . $v1_url . '" target="_blank">' . __( 'Click here for documentation on this setting.', 'all-in-one-seo-pack' ) . '</a>';
	}

	return $rtn_help_text;
}

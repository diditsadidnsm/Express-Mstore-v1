<?php

/**
 * Returns options with pro additions.
 *
 * @since free-2.3.17 #921 "autotrack.js" options added to pro only.
 *
 * @return array
 */
function aioseop_add_pro_opt( $options ) {

    $options['showseonews'] = array(
        'name'    => __( 'Show SEO News', 'all-in-one-seo-pack' ),
        'type'    => 'checkbox',
        'default' => 1,
    );

    $options['admin_bar'] = array(
        'name'    => __( 'Display Menu In Toolbar:', 'all-in-one-seo-pack' ),
        'default' => 'on',
    );

    $options['custom_menu_order'] = array(
        'name'    => __( 'Display Menu At The Top:', 'all-in-one-seo-pack' ),
        'default' => 'on',
	);

    $options['ga_track_outbound_forms'] = array(
        'name'     => __( 'Track Outbound Forms:', 'all-in-one-seo-pack' ),
        'default'  => 0,
        'condshow' => array(
            'aiosp_google_analytics_id' => array(
                'lhs' => 'aiosp_google_analytics_id',
                'op'  => '!=',
                'rhs' => '',
            ),
			'aiosp_ga_advanced_options' => 'on',
			'aiosp_gtm_container_id' => array(
				'lhs' => 'aiosp_gtm_container_id',
				'op'  => '==',
				'rhs' => '',
			),
        ),
    );

    $options['ga_track_events'] = array(
        'name'     => __( 'Track Events:', 'all-in-one-seo-pack' ),
        'default'  => 0,
        'condshow' => array(
            'aiosp_google_analytics_id' => array(
                'lhs' => 'aiosp_google_analytics_id',
                'op'  => '!=',
                'rhs' => '',
            ),
			'aiosp_ga_advanced_options' => 'on',
			'aiosp_gtm_container_id' => array(
				'lhs' => 'aiosp_gtm_container_id',
				'op'  => '==',
				'rhs' => '',
			),
        ),
    );

    $options['ga_track_url_changes'] = array(
        'name'     => __( 'Track URL Changes:', 'all-in-one-seo-pack' ),
        'default'  => 0,
        'condshow' => array(
            'aiosp_google_analytics_id' => array(
                'lhs' => 'aiosp_google_analytics_id',
                'op'  => '!=',
                'rhs' => '',
            ),
			'aiosp_ga_advanced_options' => 'on',
			'aiosp_gtm_container_id' => array(
				'lhs' => 'aiosp_gtm_container_id',
				'op'  => '==',
				'rhs' => '',
			),
        ),
    );

    $options['ga_track_visibility'] = array(
        'name'     => __( 'Track Page Visibility:', 'all-in-one-seo-pack' ),
        'default'  => 0,
        'condshow' => array(
            'aiosp_google_analytics_id' => array(
                'lhs' => 'aiosp_google_analytics_id',
                'op'  => '!=',
                'rhs' => '',
            ),
			'aiosp_ga_advanced_options' => 'on',
			'aiosp_gtm_container_id' => array(
				'lhs' => 'aiosp_gtm_container_id',
				'op'  => '==',
				'rhs' => '',
			),
        ),
    );

    $options['ga_track_media_query'] = array(               
        'name'     => __( 'Track Media Query:', 'all-in-one-seo-pack' ),
        'default'  => 0,
        'condshow' => array(
            'aiosp_google_analytics_id' => array(
                'lhs' => 'aiosp_google_analytics_id',
                'op'  => '!=',
                'rhs' => '',
            ),
			'aiosp_ga_advanced_options' => 'on',
			'aiosp_gtm_container_id' => array(
				'lhs' => 'aiosp_gtm_container_id',
				'op'  => '==',
				'rhs' => '',
			),
        ),
    );

    $options['ga_track_impressions'] = array(   
        'name'     => __( 'Track Elements Visibility:', 'all-in-one-seo-pack' ),
        'default'  => 0,
        'condshow' => array(
            'aiosp_google_analytics_id' => array(
                'lhs' => 'aiosp_google_analytics_id',
                'op'  => '!=',
                'rhs' => '',
            ),
			'aiosp_ga_advanced_options' => 'on',
			'aiosp_gtm_container_id' => array(
				'lhs' => 'aiosp_gtm_container_id',
				'op'  => '==',
				'rhs' => '',
			),
        ),
    );

    $options['ga_track_scroller'] = array(
        'name'     => __( 'Track Page Scrolling:', 'all-in-one-seo-pack' ),
        'default'  => 0,
        'condshow' => array(
            'aiosp_google_analytics_id' => array(
                'lhs' => 'aiosp_google_analytics_id',
                'op'  => '!=',
                'rhs' => '',
            ),
			'aiosp_ga_advanced_options' => 'on',
			'aiosp_gtm_container_id' => array(
				'lhs' => 'aiosp_gtm_container_id',
				'op'  => '==',
				'rhs' => '',
			),
        ),
    );

    $options['ga_track_social'] = array(
        'name'     => __( 'Track Facebook and Twitter:', 'all-in-one-seo-pack' ),
        'default'  => 0,
        'condshow' => array(
            'aiosp_google_analytics_id' => array(
                'lhs' => 'aiosp_google_analytics_id',
                'op'  => '!=',
                'rhs' => '',
            ),
			'aiosp_ga_advanced_options' => 'on',
			'aiosp_gtm_container_id' => array(
				'lhs' => 'aiosp_gtm_container_id',
				'op'  => '==',
				'rhs' => '',
			),
        ),
    );

    $options['ga_track_clean_url'] = array(
        'name'     => __( 'Ensure URL Consistency:', 'all-in-one-seo-pack' ),
        'default'  => 0,
        'condshow' => array(
            'aiosp_google_analytics_id' => array(
                'lhs' => 'aiosp_google_analytics_id',
                'op'  => '!=',
                'rhs' => '',
            ),
			'aiosp_ga_advanced_options' => 'on',
			'aiosp_gtm_container_id' => array(
				'lhs' => 'aiosp_gtm_container_id',
				'op'  => '==',
				'rhs' => '',
			),
        ),
	);

	/**
	 * Allows us to add options for Pro elsewhere besides functions_general.php.
	 * 
	 * @since 3.3.0
	 * 
	 * @param array $options Contains all Pro options that have to be printed in the General Settings menu.
	 */
	return apply_filters( 'aioseop_pro_options', $options );
}

/**
 * Returns layout with pro options added.
 *
 * @since free-2.3.17 #921 "autotrack.js" options added to pro only.
 *
 * @return array
 */
function aioseop_add_pro_layout( $layout ) {
    $layout['display']['options'][] = 'showseonews';
    $layout['display']['options'][] = 'admin_bar';
	$layout['display']['options'][] = 'custom_menu_order';
	
    $layout['google']['options'] = array_merge(
        $layout['google']['options'],
        array(
            'ga_track_outbound_forms',
            'ga_track_events',
            'ga_track_url_changes',
            'ga_track_visibility',
            'ga_track_media_query',
            'ga_track_impressions',
            'ga_track_clean_url',
            'ga_track_scroller',
			'ga_track_social',
			'gtm_container_id',
        )
    );

    return $layout;
}


//
// Taxonomy meta functions - for pre-4.4... we should look at whether we still need this
//
global $wpdb;
if ( !function_exists( 'add_term_meta' ) ) {
	if ( !isset( $wpdb->taxonomymeta ) )
		add_filter( 'add_taxonomy_metadata', 'add_tax_meta', 10, 5 );
	if ( !function_exists( 'add_tax_meta' ) ) {
		function add_tax_meta( $check, $object_id, $meta_key, $meta_value, $unique = false ) {
			$object_id = ( is_object( $object_id ) ) ? $object_id->term_id : $object_id;
			$m = get_option( 'tax_meta_' . $object_id );
			if ( isset( $m[$meta_key] ) )
				return false;
			$m[$meta_key] = $meta_value;
			update_option( 'tax_meta_' . $object_id, $m );
			return true;
		}
	}
	/**
	 * Add meta data field to a term.
	 *
	 * @param int $term_id Post ID.
	 * @param string $key Metadata name.
	 * @param mixed $value Metadata value.
	 * @param bool $unique Optional, default is false. Whether the same key should not be added.
	 * @return bool False for failure. True for success.
	 */
	function add_term_meta( $term_id, $meta_key, $meta_value, $unique = false ) {
		global $wpdb;
		$tax_unset = 1;
		if ( isset( $wpdb->taxonomymeta ) )
			$tax_unset = 0;
		if ( $tax_unset )
			$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
		$meta = add_metadata( 'taxonomy', $term_id, $meta_key, $meta_value, $unique );
		if ( $tax_unset )
			unset( $wpdb->taxonomymeta );
		return $meta;
	}
}
if ( !function_exists( 'delete_term_meta' ) ) {
	if ( !isset( $wpdb->taxonomymeta ) )
		add_filter( 'delete_taxonomy_metadata', 'aioseop_delete_tax_meta', 10, 3 );
	if ( !function_exists( 'aioseop_delete_tax_meta' ) ) {
		function aioseop_delete_tax_meta( $check, $term_id, $key ) {
			$term_id = ( is_object( $term_id ) ) ? $term_id->term_id : $term_id;
			$m = get_option( 'tax_meta_' . $term_id );
			if ( isset($m[$key] ) ) {
				unset( $m[$key] );
				update_option( 'tax_meta_' . $term_id, $m );
				return true;
			}
			return false;
		}
	}
	/**
	 * Remove metadata matching criteria from a term.
	 *
	 * You can match based on the key, or key and value. Removing based on key and
	 * value, will keep from removing duplicate metadata with the same key. It also
	 * allows removing all metadata matching key, if needed.
	 *
	 * @param int $term_id term ID
	 * @param string $meta_key Metadata name.
	 * @param mixed $meta_value Optional. Metadata value.
	 * @return bool False for failure. True for success.
	 */
	function delete_term_meta($term_id, $meta_key, $meta_value = '') {
		global $wpdb;
		$tax_unset = 1;
		if ( isset( $wpdb->taxonomymeta ) )
			$tax_unset = 0;
		if ( $tax_unset )
			$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
		$meta = delete_metadata( 'taxonomy', $term_id, $meta_key, $meta_value );
		if ( $tax_unset )
			unset( $wpdb->taxonomymeta );
		return $meta;
	}
}
if ( !function_exists( 'get_term_meta' ) ) {
	if ( !isset( $wpdb->taxonomymeta ) )
		add_filter( 'get_taxonomy_metadata', 'aioseop_get_tax_meta', 10, 4 );
	if ( !function_exists( 'aioseop_get_tax_meta' ) ) {
		function aioseop_get_tax_meta( $check, $term_id, $key, $multi = false ) {
			$t_id = ( is_object( $term_id ) ) ? $term_id->term_id : $term_id;
			$m = get_option( 'tax_meta_' . $t_id );
			if ( isset( $m[$key] ) ) return $m[$key];
			return '';
		}
	}
	/**
	 * Retrieve term meta field for a term.
	 *
	 * @param int $term_id Term ID.
	 * @param string $key The meta key to retrieve.
	 * @param bool $single Whether to return a single value.
	 * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
	 *  is true.
	 */
	function get_term_meta($term_id, $key, $single = false) {
		global $wpdb;
		$tax_unset = 1;
		if ( isset( $wpdb->taxonomymeta ) )
			$tax_unset = 0;
		if ( $tax_unset )
			$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
		$meta = get_metadata( 'taxonomy', $term_id, $key, $single );
		if ( $tax_unset )
			unset( $wpdb->taxonomymeta );
		return $meta;
	}
}
if ( !function_exists( 'update_term_meta' ) ) {
	if ( !isset( $wpdb->taxonomymeta ) )
		add_filter( 'update_taxonomy_metadata', 'aioseop_update_tax_meta', 10, 5 );
	if ( !function_exists( 'aioseop_update_tax_meta' ) ) {
		function aioseop_update_tax_meta( $check, $object_id, $meta_key, $meta_value, $unique = false ) {
			$object_id = ( is_object( $object_id ) ) ? $object_id->term_id : $object_id;
			$m = get_option( 'tax_meta_' . $object_id );
			if ( !isset( $m[$meta_key] ) || ( $m[$meta_key] !== $meta_value ) ) {
				$m[$meta_key] = $meta_value;
				update_option( 'tax_meta_' . $object_id, $m );
			}
			return true;
		}
	}
	/**
	 * Update term meta field based on term ID.
	 *
	 * Use the $prev_value parameter to differentiate between meta fields with the
	 * same key and term ID.
	 *
	 * If the meta field for the term does not exist, it will be added.
	 *
	 * @param int $term_id Term ID.
	 * @param string $key Metadata key.
	 * @param mixed $value Metadata value.
	 * @param mixed $prev_value Optional. Previous value to check before removing.
	 * @return bool False on failure, true if success.
	 */
	function update_term_meta($term_id, $meta_key, $meta_value, $prev_value = '') {
		global $wpdb;
		$tax_unset = 1;
		if ( isset( $wpdb->taxonomymeta ) )
			$tax_unset = 0;
		if ( $tax_unset )
			$wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
		$meta = update_metadata( 'taxonomy', $term_id, $meta_key, $meta_value, $prev_value );
		if ( $tax_unset )
			unset( $wpdb->taxonomymeta );
		return $meta;
	}
}

if ( ! function_exists( 'aioseop_opengraph_display' ) ) {
	/**
	 * Returns opengraph module display options.
	 * Enables social SEO on taxonomies.
	 * @since 2.4.14
	 *
	 * @global array $aioseop_options Plugin options.
	 *
	 * @param array $display List of available displays.
	 *
	 * @return array
	 */
	function aioseop_opengraph_display( $display ) {
		global $aioseop_options;
		if( ! is_array( $display ) ){
			$display = array(); // We're being treated an array below, but if there are no post types we're a string.			
		}
		if( isset( $aioseop_options[ 'aiosp_taxactive' ] ) && !empty( $aioseop_options[ 'aiosp_taxactive' ] ) ){
			foreach( $aioseop_options[ 'aiosp_taxactive' ] as $tax ) {
				$display[] = 'edit-' . $tax;
			}
		}
		return $display;
	}
	add_filter( 'aioseop_opengraph_display', 'aioseop_opengraph_display' );
}

if ( ! function_exists( 'aioseop_ga_enable_autotrack' ) ) {
	/**
	 * Returns flag indicating if autotrack should be enabled or not.
	 * @since free-2.3.17
	 *
	 * @param bool  $enabled Current flag.
	 * @param array $options Options to check.
	 *
	 * @return bool
	 */
	function aioseop_ga_enable_autotrack( $enabled, $options ) {
		return ! empty( $options['aiosp_ga_advanced_options'] ) 
			&& ( ! empty( $options['aiosp_ga_track_outbound_links'] )
				|| ! empty( $options['aiosp_ga_track_outbound_forms'] )
				|| ! empty( $options['aiosp_ga_track_events'] )
				|| ! empty( $options['aiosp_ga_track_url_changes'] )
				|| ! empty( $options['aiosp_ga_track_visibility'] )
				|| ! empty( $options['aiosp_ga_track_media_query'] )
				|| ! empty( $options['aiosp_ga_track_impressions'] )
				|| ! empty( $options['aiosp_ga_track_scroller'] )
				|| ! empty( $options['aiosp_ga_track_social'] )
				|| ! empty( $options['aiosp_ga_track_clean_url'] )
			);
	}
	add_filter( 'aioseop_ga_enable_autotrack', 'aioseop_ga_enable_autotrack', 10, 2 );
}

if ( ! function_exists( 'aioseop_ga_extra_options' ) ) {
	/**
	 * Returns extra options for google analytics.
	 * @since free-2.3.17
	 *
	 * @param array $extra_options Google analytics current extra options.
	 * @param array $options       Social settings / options to check.
	 *
	 * @return array
	 */
	function aioseop_ga_extra_options( $extra_options, $options ) {
		if ( ! empty( $options['aiosp_ga_advanced_options'] ) ) {
			if ( ! empty( $options['aiosp_ga_track_outbound_forms'] ) ) {
				$extra_options[] = 'ga(\'require\', \'outboundFormTracker\');';
			}
			if ( ! empty( $options['aiosp_ga_track_events'] ) ) {
				$extra_options[] = 'ga(\'require\', \'eventTracker\');';
			}
			if ( ! empty( $options['aiosp_ga_track_url_changes'] ) ) {
				$extra_options[] = 'ga(\'require\', \'urlChangeTracker\');';
			}
			if ( ! empty( $options['aiosp_ga_track_visibility'] ) ) {
				$extra_options[] = 'ga(\'require\', \'pageVisibilityTracker\');';
			}
			if ( ! empty( $options['aiosp_ga_track_media_query'] ) ) {
				$extra_options[] = 'ga(\'require\', \'mediaQueryTracker\');';
			}
			if ( ! empty( $options['aiosp_ga_track_impressions'] ) ) {
				$extra_options[] = 'ga(\'require\', \'impressionTracker\');';
			}
			if ( ! empty( $options['aiosp_ga_track_scroller'] ) ) {
				$extra_options[] = 'ga(\'require\', \'maxScrollTracker\');';
			}
			if ( ! empty( $options['aiosp_ga_track_social'] ) ) {
				$extra_options[] = 'ga(\'require\', \'socialWidgetTracker\');';
			}
			if ( ! empty( $options['aiosp_ga_track_clean_url'] ) ) {
				$extra_options[] = 'ga(\'require\', \'cleanUrlTracker\');';
			}
		}
		return $extra_options;
	}
	add_filter( 'aioseop_ga_extra_options', 'aioseop_ga_extra_options', 10, 2 );
}

if ( ! function_exists( 'aiosp_pro_display_settings' ) ) {
	/**
	 * Filters "aiosp_display_settings".
	 * Checks and adds/removes settings.
	 * @since 2.4.15
	 *
	 * @global object $aiosp All-in-one-seo plugin class.
	 *
	 * @param array  $settings Current settings.
	 * @param string $location Current generic location.
	 * @param array  $current  Current location with specific data.
	 *
	 * @return array settings.
	 */
	function aiosp_pro_display_settings( $settings ) {
				
		$screen = get_current_screen();
		if( 'term' !== $screen->base ){
			return $settings; // Bail if we're not on the edit taxonomy screen.
		}
		
		global $aiosp;
		if ( ! isset( $settings['aiosp_title']['placeholder'] ) ) {
			// Get term title and description from snippet
			$info = $aiosp->get_page_snippet_info();
			extract( $info );
			// Apply placeholders
			$settings['aiosp_title']['placeholder'] = apply_filters( 'aioseop_opengraph_placeholder', $title );
			$settings['aiosp_description']['placeholder'] = apply_filters( 'aioseop_opengraph_placeholder', $description );
		}
		return $settings;
	}
	add_filter( 'aiosp_display_settings', 'aiosp_pro_display_settings', 99 );
}

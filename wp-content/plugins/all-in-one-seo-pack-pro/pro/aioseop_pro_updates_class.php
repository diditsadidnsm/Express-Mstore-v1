<?php
/**
 * @package All-in-One-SEO-Pack
 */

/**
 * AIOSEOP Pro Updates class.
 *
 * Handle detection of new plugin version updates, migration of old settings,
 * new WP core feature support, etc.
 */
class aioseop_pro_updates {
	/**
	 * Constructor
	 */
	function __construct() {

	}

	function version_updates() {
		global $aiosp, $aioseop_options;
		if ( empty( $aioseop_options ) ) {
			$aioseop_options = get_option( $aioseop_options );
		}

		// See if we are running a newer version than last time we checked.
		if ( !isset( $aioseop_options ) || empty( $aioseop_options ) || !isset( $aioseop_options['last_active_version'] ) || version_compare( $aioseop_options['last_active_version'], AIOSEOP_VERSION, '<' ) ) {
			// Last known running plugin version
			$last_active_version = isset( $aioseop_options['last_active_version'] ) ? $aioseop_options['last_active_version'] : '0.0';

			// Do upgrades based on previous version
			$this->do_version_updates( $last_active_version );

			// Save the current plugin version as the new last_active_version
			$aioseop_options['last_active_version'] = AIOSEOP_VERSION;
			$aiosp->update_class_option( $aioseop_options );
		}

		/**
		 * Perform updates that are dependent on external factors, not
		 * just the plugin version.
		 */
		$this->do_feature_updates();

	}

	function do_version_updates( $old_version ) {
		global $aioseop_options;

		/*
		if ( ( AIOSEOPPRO && version_compare( $old_version, '2.4.3', '<' ) ) ) {
	   		$this->some_pro_function_201603();
		}
		*/
		/*
		if ( ( AIOSEOPPRO && version_compare( $old_version, '2.5', '<' ) ) ) {
			// Do changes needed for 2.5/2.6... etc
		}
		*/

		if ( ( AIOSEOPPRO && version_compare( $old_version, '2.4.9', '<' ) ) ) {

			$this->download_translation_201608();
		}
	}

	function do_feature_updates() {
		global $aioseop_options;

		// We don't need to check all the time. Use a transient to limit frequency.
		if ( get_site_transient( 'aioseop_update_check_time' ) ) return;

		// We haven't checked recently. Reset the timestamp, timeout 6 hours.
		set_site_transient( 'aioseop_update_check_time', time(), apply_filters( 'aioseop_update_check_time', 3600 * 6 ) );

		if ( ! ( isset( $aioseop_options['version_feature_flags']['term_meta_migrated'] ) &&
		 $aioseop_options['version_feature_flags']['term_meta_migrated'] === 'yes' ) ) {
	   		$this->migrate_term_meta_201603();
		}
	}

	/*
	 * Functions for specific version milestones
	 */

	function download_translation_201608() {

		add_action( 'in_admin_footer', array( $this, 'download_translation' ) );

	}

	function download_translation() {

		$locale = get_locale();

		include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
		$lpu = new Language_Pack_Upgrader();

		// We need to check that the translation exists first.

		$exists = wp_remote_get("https://downloads.wordpress.org/translation/plugin/all-in-one-seo-pack/2.3.8/$locale.zip");

		if($exists['headers']['content-type'] !== 'application/zip') {
			return;
		}

		$update =
			array(
				'type'       => 'plugin',
				'slug'       => 'all-in-one-seo-pack',
				'language'   => $locale,
				'version'    => '2.3.8',
				'updated'    => '2016-02-26 12:53:29',
				'package'    => "https://downloads.wordpress.org/translation/plugin/all-in-one-seo-pack/2.3.8/$locale.zip",
				'autoupdate' => '1',
			);

		$language_update = $update;

		$hook_extra = array(
			'language_update_type' => 'plugin',
			$language_update,
		);

		$destination = WP_LANG_DIR . '/plugins';

		$update_array = array(
			'package'                     => "https://downloads.wordpress.org/translation/plugin/all-in-one-seo-pack/2.3.8/$locale.zip",
			'destination'                 => $destination,
			'clear_working'               => 0,
			'abort_if_destination_exists' => 0,
			'clear_destination'           => 0,
			'is_multi'                    => 1,
			$hook_extra,

		);

		$lpu->run( $update_array );

	}

	/**
	 * Migrate old term meta to use native WP functions.
	 */
	function migrate_term_meta_201603() {
		global $wpdb, $aiosp, $aioseop_options;
		/*
		// Check WP db version to be sure term meta tables exist
		// if not, bail
		// if yes:
		//   migrate old meta
		//   update 'term_meta_migrated' option flag
		*/

		// Bail if native WP term meta table is not installed.
        if ( intval( get_option( 'db_version' ) ) < 34370 ) {
            return false;
        }

        /**
         * Migrate tax_meta_% entries from options table
         */
        $query = "SELECT option_name, option_value FROM {$wpdb->prefix}options WHERE option_name LIKE 'tax_meta_%'";
        $taxmeta = $wpdb->get_results( $query );
        if ( is_array( $taxmeta ) ) {
	        foreach ( $taxmeta as $meta ) {
	        	$name = $meta->option_name;
	        	$mvals = maybe_unserialize( $meta->option_value );
	        	$termid = intval( str_replace( 'tax_meta_', '', $name ) );
	        	foreach( $mvals as $mkey => $mval ) {
	        		// Set 'unique' param to TRUE so we don't overwrite existing
	        		add_term_meta( $termid, $mkey, $mval, true );
	        	}
	        	// Done with the old 'tax_meta_foo' option now.
	        	delete_option( $name );
	        }
	    }

        /**
         * Compat with Taxonomy Metadata plugin. Use an outer join with exclusion
         * to migrate entries from the plugin's 'taxonomymeta' table to the
         * native WordPress 'termmeta' table, if a corresponding entry doesn't
         * already exist.
         */
        $taxmeta_table = "{$wpdb->prefix}taxonomymeta";
        if ( $wpdb->get_var("SHOW TABLES LIKE '$taxmeta_table'") === $taxmeta_table ) {
        	$query = "INSERT INTO {$wpdb->termmeta} (term_id, meta_key, meta_value)
        	  SELECT taxm.taxonomy_id as term_id, taxm.meta_key as meta_key, taxm.meta_value as meta_value 
        	  FROM {$wpdb->termmeta} termm 
        	  RIGHT JOIN $taxmeta_table taxm 
        	  ON (termm.term_id=taxm.taxonomy_id AND termm.meta_key=taxm.meta_key) 
        	  WHERE termm.meta_id IS NULL";

        	@$wpdb->query( $query );
    	}

        $aioseop_options['version_feature_flags']['term_meta_migrated'] = 'yes';
        $aiosp->update_class_option( $aioseop_options );
	}

}

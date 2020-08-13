<?php

require( AIOSEOP_PLUGIN_DIR . 'pro/plugin-updates/plugin-update-checker.php' );

class SFWD_Update_Checker extends PluginUpdateChecker_3_1 {
	var $plugin_name;
	var $plugin_basename;
	var $license_key;
	var $options_page;
	var $renewal_page;

	function __construct($metadataUrl, $pluginFile, $slug = '', $checkPeriod = 12, $optionName = ''){
		parent::__construct( $metadataUrl, $pluginFile, $slug, $checkPeriod, $optionName );
	}

	function has_update() {
		$updates = (object)Array( 'response' => Array() );
		$updates = $this->injectUpdate( $updates );
		return ( !empty( $updates->response ) && !empty( $updates->response[$this->plugin_basename] ) );
	}

	/**
	 * Check license key format.
	 */
	function check_key_format( $license_key ) {
		return preg_match( "/^(?:MT-)?[A-Z\d]{14}$|^[A-Z\d]{17}$/", $license_key );
	}

	/**
	 * Get the license key and sanitize it.
	 */
	function get_license_key( $license_key = null ) {
		if ( $license_key == null )
			$license_key = $this->license_key;
		$license_key = strtoupper( trim( $license_key ) );
		return $license_key;
	}

	/**
	 * Get the verification code.
	 */
	function get_verification_code( $license_key = null ) {
		$license_key = $this->get_license_key( $license_key );
		return strtoupper( str_replace( "=", '', base64_encode( sha1( $license_key, true ) ) ) );
	}

	/**
	 * Check the license key.
	 */
	function check_license_key( $license_key = null ) {
		return true;

		$license_key = $this->get_license_key( $license_key );
		return $this->check_key_format( $license_key );
	}

	/**
	 * Alert the user to enter the license key, if needed.
	 * @since ?
	 * @since 2.4.16 Update message shows whether or not you've entered a key.
	 */

	function key_warning() {

	}

	/**
	 * Check sto see if license is expired.
	 **/
	function is_expired() {
		$updates = array( 'response' => array() );
		$updates = $this->injectUpdate( $updates );

		if ( empty( $updates->response[$this->plugin_basename]->upgrade_notice ) ) {
			return false;
		}

		if ( is_string( $updates->response[$this->plugin_basename]->upgrade_notice ) && false !== strpos( $updates->response[$this->plugin_basename]->upgrade_notice, 'support and updates subscription has expired' ) ){
			return true;
		}
		return false;
	}

	/**
	 * Add row to Plugins page with licensing information, if license key is invalid or not found.
	 */
	function add_plugin_row() {
		return;

	}

	/**
	 * Get update information back from the server, display to the user on the plugins page.
	 */
	function update_message( $plugin_data, $r ) {
		echo " " . __("Notice: ", 'all-in-one-seo-pack' ) . $r->upgrade_notice;
	}

	function license_change_check( $options, $location ) {
		if ( ( $location == null ) && isset( $options['aiosp_license_key'] ) ) {
			if ( ( $options['aiosp_license_key'] != $this->license_key ) && $this->check_license_key( $options['aiosp_license_key'] ) ){
				delete_transient( $this->slug . '_updates_checked' );
			}
			$options['aiosp_license_key'] = sanitize_text_field( $options['aiosp_license_key'] ); // Trim whitespace.
			$this->license_key = $options['aiosp_license_key'];
		}
		return $options;
	}

	function update_check( $options, $location ) {
		if ( $location == null ) {
			if ( $this->check_license_key() ) {
				if ( get_transient( $this->slug . '_updates_checked' ) ) {
					// $sfwd_PucScheduler = new PucScheduler_3_1();
					$this->checkForUpdates(); // Does this need to be maybe?
				}
				else
					$this->checkForUpdates();
				set_transient( $this->slug . '_updates_checked', true, 300 );
			}
		}
	}

	function get_site_url(){
		$site_url = get_site_url();
		if( ! empty($site_url)){
			return $site_url;
		}
		return 'none';
	}

	// Make a function for each of these to see if we actually get a result. How is the plugins.php page doing? Maybe do some testing to see if we're using a lot of memory.
	function add_secret_key($query) {

		//$aiosp_options = get_option( 'aioseop_options' );


		$query['plugin'] = $this->slug;
		$query['secret'] = $this->license_key;
		// sending the theme breaks it so we will just disable for now
		$query['site_url'] = get_site_url();
		$query['home_url'] = get_home_url();
		$query['wp_version'] = get_bloginfo('version');
		$query['admin_email'] = get_bloginfo('admin_email');
		$query['language'] = get_bloginfo('language');
		$query['permalink'] = get_option( 'permalink_structure' );
                $query['phpversion'] = phpversion();

		//$query['theme'] = wp_get_theme();
		//$query['plugins'] = get_option('active_plugins');

		// For some reason sending this options array breaks updates on the live semperplugins server but not dev... revisit this later.
	//	$query['aiosp_options'] = $aiosp_options;

//$query['multisite'

		return $query;




	}
}

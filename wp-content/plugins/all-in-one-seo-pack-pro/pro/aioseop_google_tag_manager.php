<?php
/**
 * Contains the AIOSEOP_Pro_Google_Tag_Manager class.
 * 
 * @since 3.3.0
 */

if ( ! class_exists( 'AIOSEOP_Pro_Google_Tag_Manager' ) ) {

	/**
	 * Contains all Google Tag Manager Code.
	 * 
	 * @since 3.3.0
	 */
	class AIOSEOP_Pro_Google_Tag_Manager {

		/**
		 * Constructor for AIOSEOP_Pro_Google_Tag_Manager class.
		 * 
		 * @since 3.3.0
		 */
		public function __construct() {
			$this->set_gtm_option();
			$this->output_gtm_container_id();
		}
		
		/**
		 * Sets the aiosp_gtm_container_id option if it isn't set.
		 *
		 * @since 3.3.0
		 * 
		 * @return void
		 */
		private function set_gtm_option() {
			global $aioseop_options;

			if ( ! isset( $aioseop_options['aiosp_gtm_container_id'] ) ) {
				$aioseop_options['aiosp_gtm_container_id'] = '';
				update_option( 'aioseop_options', $aioseop_options );
			}
		}

		/**
		 * Outputs the Google Tag Manager Container ID code into the source code.
		 * 
		 * @since 3.3.0
		 * 
		 * @return void;
		 */
		private function output_gtm_container_id() {
			global $aioseop_options;
	
			/**
			 * Allows users to manipulate the Google Tag Manager Container ID.
			 * 
			 * @since 3.3.0
			 * 
			 * @param string The Google Tag Manager Container ID.
			 */
			$container_id = apply_filters( 'aioseop_gtm_container_id', $aioseop_options['aiosp_gtm_container_id'] );

			if ( ! $this->should_output_gtm_container_id( $container_id ) ) {
				return;
			}


			// Disable advanced GA features.
			add_filter( 'aioseop_pro_gtm_enabled', '__return_true' );
	
			$this->print_gtm_head( $container_id );
			$this->print_gtm_body( $container_id );
		}

		/**
		 * Checks whether we should output the Google Manager Container ID for the current page.
		 * 
		 * @since 3.3.0
		 * 
		 * @return void
		 */
		private function should_output_gtm_container_id( $container_id ) {
			/**
			 * Allows users to prevent the plugin from outputting the Google Tag Manager Container ID.
			 * 
			 * @since 3.3.0
			 * 
			 * @param string $container_id The Google Tag Manager Container ID.
			 * @return bool The default value is 'false'.
			 */
			$disable_gtm = apply_filters( 'aioseop_disable_google_tag_manager', __return_false() );

			if ( 
					$disable_gtm ||
					is_admin() ||
					empty( $container_id ) ||
					! preg_match( '/GTM-.{6}/', $container_id ) 
			) {
				return false;
			}
			return true;
		}

		/** 
		 * Prints the Google Tag Manager code in the HEAD section.
		 * 
		 * @since 3.3.0
		 * 
		 * @param string $container_id The Google Tag Manager Container ID.
		 * @return void;
		 */
		private function print_gtm_head( $container_id ) {
			printf( "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','%s');</script>\n", $container_id );
		}

		/** 
		 * Prints the Google Tag Manager code in the BODY section.
		 * 
		 * @since 3.3.0
		 *  
		 * @param string $container_id The Google Tag Manager Container ID.
		 * @return void;
		 */
		private function print_gtm_body( $container_id ) {
			wp_enqueue_script( 
				'aioseop-google-tag-manager',
				AIOSEOP_PLUGIN_URL . 'pro/js/aioseop-google-tag-manager.js',
				array(),
				AIOSEOP_VERSION,
				true
			);
		
			$localized_container_id = array( 'gtmContainerId' => $container_id, );
		
			wp_localize_script( 
				'aioseop-google-tag-manager', 
				'aioseopGoogleTagManager', 
				$localized_container_id 
			);
		}	
	}

	add_filter( 'aioseop_pro_options', 'aioseop_pro_add_gtm_option' );

	if ( ! function_exists( 'aioseop_pro_add_gtm_option' ) ) {

		/**
		 * Adds the Google Tag Manager Container ID field to the General Settings menu.
		 * 
		 * @since 3.3.0
		 * 
		 * @param array $options Contains all options that have to be printed.
		 * @return array $options Contains all existing options + the Google Tag Manager Container ID option.
		 */
		function aioseop_pro_add_gtm_option( $options ) {
			$options['gtm_container_id'] = array(
				'name'        => 'Google Tag Manager Container ID',
				'type'        => 'text',
				'placeholder' => 'GTM-XXXXXX',
				'default'     => null,
			);
			return $options;
		}
	}
}

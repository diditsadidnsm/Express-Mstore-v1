<?php

/**
 * @package All-in-One-SEO-Pack
 */
/**
 * The general functions class for Pro.
 */
if ( ! class_exists( 'AIO_ProGeneral' ) ) {
	class AIO_ProGeneral extends All_in_One_SEO_Pack_Module {

		function __construct() {
			//		$this->name = __('Performance', 'all-in-one-seo-pack');		// Human-readable name of the plugin
			//		$this->prefix = 'aiosp_performance_';						// option prefix
			$this->file = __FILE__;                                    // the current file
			parent::__construct();
			$this->add_hooks();
		}


		/*
		*from get_current_options in aioseop_module_class.php
		*/
		static public function getprotax( $get_opts = '', $prefix = null, $location = null ) {

			if ( is_admin() && isset( $_GET['tag_ID'] ) ) {
				$get_opts = get_term_meta( $_GET['tag_ID'], '_' . $prefix . $location, true );
			} else {
				$queried_object = get_queried_object();
				if ( ! empty( $queried_object ) && ! empty( $queried_object->term_id ) ) {
					$get_opts = get_term_meta( $queried_object->term_id, '_' . $prefix . $location, true );
				}
			}

			return $get_opts;
		}


		/*
		*from $this->layout in construct of aioseop_class.php
		*/
		static public function getprooptions( $opts ) {

			$opts['cpt']['options'] = array(
				"enablecpost",
				"cpostactive",
				"taxactive",
				"cpostadvanced",
				"cposttitles",
			);

			return $opts;

		}


		static public function aioseop_embed_handler_html( $return, $url, $attr ) {
			global $aioseop_modules;
			global $post;
			if ( ! empty( $url ) ) {
				$module = $aioseop_modules->return_module( "All_in_One_SEO_Pack_Video_Sitemap" );
				$module->oembed_discovery( $return, $url, null, $post->ID );
			}

			return $return;
		}

		/**
		* Add all additional hooks.
		*/
		private function add_hooks() {
			add_filter( 'aiosp_sitemap_post_filter', array( $this, 'filter_posts' ), 10, 2 );
			add_action( 'registered_taxonomy', 'aioseop_taxonomy_post_register', 10, 1 );
		}

		/**
		* Filter the posts after they have been retrieved from the database.
		*/
		function filter_posts( $posts, $args ) {
			$post_types = $args['post_type'];
			if ( ! is_array( $post_types ) ) {
				$post_types = array( $args['post_type'] );
			}
			$common = array_intersect( $post_types, array( 'any', 'product' ) );
			if ( 
					! empty( $common )
					&& class_exists( 'WooCommerce' )
					&& apply_filters( 'aisop_woocommerce_hide_hidden_products', true ) 
			) {
				$posts	= $this->woocommerce( $posts, $args );
			}
			return $posts;
		}

		/**
		* Return only the visible products.
		*/
		private function woocommerce( $posts, $args ) {
			$showing	= array();
			foreach ( $posts as $post ) {
				$showing[ $post->ID ]	= $post;
			}

			$products	= wc_get_products( array( 'post__in' => array_keys( $showing ) ) );
			if ( $products ) {
				foreach ( $products as $product ) {
					if ( ! $product->is_visible() ) {
						unset( $showing[ $product->get_id() ] );
					}
				}
				$posts	= array_values( $showing );
			}
			return $posts;
		}
	}
}

new AIO_ProGeneral();

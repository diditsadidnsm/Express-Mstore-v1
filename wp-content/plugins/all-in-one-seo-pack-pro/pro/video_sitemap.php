<?php
/**
 * @package All-in-One-SEO-Pack
 */
/**
 * The Video Sitemap class.
 */
if ( !class_exists( 'All_in_One_SEO_Pack_Sitemap' ) ) {
	include_once( AIOSEOP_PLUGIN_DIR . "modules/aioseop_sitemap.php" );
}
if ( class_exists( 'All_in_One_SEO_Pack_Sitemap' ) && ( !class_exists( 'All_in_One_SEO_Pack_Video_Sitemap' ) ) ) {
	class All_in_One_SEO_Pack_Video_Sitemap extends All_in_One_SEO_Pack_Sitemap {

        private static $_wp_oembed = null;

		/**
		 * @var array $_fields The 2D array containing the fields of the sitemap.
		 */
		private static $_fields = array(
				'thumbnail_url' => 'video:thumbnail_loc',
				'title' => 'video:title',
				'description' => 'video:description',
				'html' => 'video:player_loc',
				'duration' => 'video:duration',
				'author_name' => 'video:uploader',
		);

		/**
		 * @var array $_fields The 2D array containing the field vs. the field type of the sitemap.
		 */
		private static $_field_types = array(
				'video:duration' => 'int',
		);


		function __construct( ) {
			$this->name = __( 'Video Sitemap', 'all-in-one-seo-pack' );	// Human-readable name of the plugin
			$this->prefix = 'aiosp_video_sitemap_';						// option prefix
			$this->file = __FILE__;									// the current file
			parent::__construct();
			$this->default_options['filename']['default'] = 'video-sitemap';

			$this->layout['status']['help_link'] = 'https://semperplugins.com/documentation/video-sitemap/';

			$this->layout['default']['options'][] = 'custom_fields';
			$this->default_options['custom_fields'] = Array( 'name' => __( 'Include Custom Fields', 'all-in-one-seo-pack' ));

			unset( $this->layout['excl_pages']['options'][0] );	// Exclude categories.
			unset( $this->layout['priorities']['options'][2] );
			unset( $this->layout['frequencies']['options'][2] );
			unset( $this->layout['default']['options'][5] ); // Exclude taxonomies.
			unset( $this->layout['default']['options'][6] ); // Exclude date archives.
			
            if ( is_null( self::$_wp_oembed ) ) {
            	global $wp_version;

            	if ( version_compare( '5.3', $wp_version, '<' )) {
					include_once( ABSPATH . 'wp-includes/class-oembed.php' );
				} else {
					include_once( ABSPATH . 'wp-includes/class-wp-oembed.php' );
				}

                // instead of using the traditional action 'wp_oembed_add_provider', which adds the providers to its own static wp_oembed object, we will use the filter 'oembed_providers' because we are loading our own instance of wp_oembed
                add_filter('oembed_providers', array( $this, 'add_oembed_providers' ) );
				self::$_wp_oembed = new WP_oEmbed();
            }

			add_filter( $this->prefix . 'prio_item_filter', Array( $this, 'do_post_video'), 10, 3 );
			add_filter( 'embed_oembed_html', Array( $this, 'oembed_discovery' ), 10, 4 );
			add_filter( 'save_post', Array( $this, 'scan_post' ) );
			add_filter( $this->prefix . 'xml_namespace', Array( $this, 'add_namespace' ) );
			add_action( 'aiosp_activate_video_sitemap', array( $this, 'activate_module' ) );
			add_action( $this->prefix . 'scan', array( $this, 'scan_all_posts' ) );
			add_action( 'admin_notices', array( $this, 'scan_admin_notice' ) );
			add_action( $this->prefix . 'settings_update', array( $this, 'do_sitemaps' ) );

			add_filter( $this->prefix . 'show_taxonomy', array( $this, 'show_taxonomy' ), 10, 1 );

			// Good for testing.
			//add_action('admin_init', array($this, 'scan_all_posts' ));
		}

		/**
		 * Show Taxonomy
		 *
		 * Wrapper around the filter that can be used to NOT remove the taxonomies from the sitemap.
		 *
		 * @since 3.0
		 *
		 * @param array $taxonomies An array of taxonomies.
		 * @return bool|array A false if the given taxonomies have to be hidden or else the array of terms that need to be shown.
		 */
		function show_taxonomy( $taxonomies ) {
			/**
			 * {$module_prefix} Remove Taxonomy
			 *
			 * Toggles whether the taxonomies should be shown or not.
			 *
			 * @since 3.0
			 *
			 * @param array $show       An array of taxonomies that need to be shown.
			 * @param array $taxonomies An array of taxonomies.
			 */
			return apply_filters( "{$this->prefix}remove_taxonomy", false, $taxonomies );
		}

		/**
		 * Called when the module is activated.
		 */
		public function activate_module() {
			if ( ! wp_next_scheduled( $this->prefix . 'scan' ) ) {
				// start this in 10s.
				wp_schedule_single_event( time() + 10, $this->prefix . 'scan' );
				set_transient( $this->prefix . 'scan', 'on' );
				// show a notification.
				do_action( 'admin_notices' );
			}
		}

		/**
		 * Shows the admin notice for the background scan.
		 */
		public function scan_admin_notice() {
			$status = get_transient( $this->prefix . 'scan' );
			if ( false === $status ) {
				return;
			}

			$msg = null;
			// the status will either give 'on' or a number. The number indicates when this is likely to finish.
			if ( 'on' === $status ) {
				$time = $this->calculate_scan_time();
				set_transient( $this->prefix . 'scan', time() + $time, DAY_IN_SECONDS );
			} elseif ( 'done' === $status ) {
				$msg = __( 'Video sitemap scan completed successfully!', 'all-in-one-seo-pack' );
				delete_transient( $this->prefix . 'scan' );
			}

			if ( is_numeric( $status ) ) {
				$diff = $status - time();
				$h = $diff / HOUR_IN_SECONDS;
				$m = ( $diff % HOUR_IN_SECONDS ) / MINUTE_IN_SECONDS;
				$check = '';
				if ( $h > 0 ){
					$check .= round( $h ) . ' hour(s) ';
				}
				if ( $m > 0 ){
					$check .= round( $m ) . ' minute(s)';
				}
				if ( empty( $check ) ) {
					$check = __( 'a short while', 'all-in-one-seo-pack' );
				}
				$msg = sprintf( __( 'Video sitemap scan in progress. Please check again in %s.', 'all-in-one-seo-pack' ), $check );
			}
			
			if ( $msg ) {
				echo sprintf( '<div class="notice notice-info"><p>%s</p></div>', $msg );
			}
		}

		/**
		 * Gets the posts query for the specified args.
		 */
		private function get_post_query( $args = array() ) {
			$default = array(
				'post_status' => 'publish',
				'numberposts' => PHP_INT_MAX,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'orderby' => 'ID',
				'order' => 'ASC',
			);

			$types = $this->options[ "{$this->prefix}posttypes" ];
			if ( ! is_array( $types ) || in_array( 'all', $types ) ) {
				$default['post_type'] = 'any';
			} else  {
				$default['post_type'] = $types;
			}

			$query = new WP_Query( array_merge( $default, $args ) );
			//error_log(print_r($query,true));
			return $query;
		}

		/**
		 * Calculates the total approximate time that the background scan will take.
		 */
		private function calculate_scan_time() {
			$seconds_per_link = 2;
			$query = $this->get_post_query();

			$time = 0;
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$contents = $this->get_post_content( $query->post->ID, $query->post );
					$links= $this->get_video_links( $contents, false );
					$time += count( $links ) * $seconds_per_link;
				}
			}

			return $time;
		}

		/**
		 * Scan all supported posts for videos.
		 */
		public function scan_all_posts() {
			set_time_limit(0);
			$query = $this->get_post_query( array( 'fields' => 'ids' ) );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$this->scan_post( $query->post );
				}
			}

			$status = get_transient( $this->prefix . 'scan' );
			if ( is_numeric( $status ) ) {
				set_transient( $this->prefix . 'scan', 'done', DAY_IN_SECONDS );
			}
		}

        function add_oembed_providers( $providers ) {
            global $wp_version;

            $providers['#https?://(www\.)?videopress.com/v/.*#i'] = array(
                'http://public-api.wordpress.com/oembed?for=' . urlencode(AIOSEOP_PLUGIN_NAME),
                true
            );

            $providers['#https?:\/\/(.+)?(wistia.com|wi.st)\/(medias|embed)\/.*#i'] = array(
                'http://fast.wistia.com/oembed',
                true
            );

            $providers['#https?://(www\.)?flickr\.com/.*#i'] = array(
                'https://www.flickr.com/services/oembed?format={format}',
                true
            );

            if ( version_compare( $wp_version, '4.0.0', '>=' ) ) {
                // viddler was removed in WP 4.0.0
                // also, remove the provider in case it is added back in a later WP version
                $providers['#https?://(www\.)?viddler.com/v/.*#i'] = array(
                    'http://www.viddler.com/oembed/',
                    true
                );
            }
            return $providers;
        }

		function add_namespace( $ns ) {
			$ns['xmlns:video'] = 'http://www.google.com/schemas/sitemap-video/1.1';
			return $ns;
		}
		/** Initialize options, after constructor **/
		function load_sitemap_options() {
			parent::load_sitemap_options();
				add_filter( $this->prefix . 'post_query', Array( $this, 'fetch_videos_only' ) );
				add_filter( $this->prefix . 'post_counts', Array( $this, 'count_videos_only' ), 10, 2 );
		}
		/** Custom settings **/
		function display_custom_options( $buf, $args ) {
			return parent::display_custom_options( $buf, $args );
		}

		/**
		 * Rewrite sitemap.
		 *
		 * Output sitemaps dynamically based on rewrite rules.
		 *
		 * @since ?
		 * @since 3.0 Return a (string) value. #2190 & #2339
		 *
		 * @param     $sitemap_type
		 * @param int $page
		 * @return string
		 */
		function do_rewrite_sitemap( $sitemap_type, $page = 0 ) {
				return parent::do_rewrite_sitemap( $sitemap_type, $page );
		}

		function do_post_video( $pr_info, $post, $args ) {
			if ( !empty( $post ) ) {
				$post_id = $post->ID;
				$opts = get_post_meta( $post_id, '_aioseop_oembed_info', true );
				if ( !empty( $opts ) ) {
					if ( ! array_key_exists( 'id', $opts ) ) {
						$opts['id'] = $post_id;
					}
					$pr_info["video:video"] = Array();
                    $videos     = array();
					foreach( $opts as $o ) {
						$videos[] = $this->parse_video_opts( $o );
                    }

                    if ( $videos ) {
						// weed out duplicate videos e.g. embedding daily motion causes it to insert 2 videos
						$urls	= array();
                        foreach ( $videos as $video ) {
                            if ( ! is_array( $video ) ) {
                                $video  = array( $video );
                            }
                            foreach ( $video as $vid ) {
								if ( ! in_array( $vid['video:player_loc'], $urls ) ) {
	                                $pr_info["video:video"][] = $vid;
									$urls[]	= $vid['video:player_loc'];
								}
                            }
                        }
                    }
					return $pr_info;
				}
			}
			return Array();
		}
		function fetch_videos_only( $args ) {
			$args['meta_query'] = Array(
				Array( 'key' => '_aioseop_oembed_info', 'compare' => 'EXISTS' )
			);
			return $args;
		}
		function count_videos_only( $counts, $args ) {
			if ( !empty( $counts ) ) {
				$status = 'inherit';
				if ( !empty( $args['post_status'] ) ) $status = $args['post_status'];
				if ( !is_array( $counts ) ) {
					$counts = Array( $args['post_type'] => $counts );
				}
				foreach( $counts as $post_type => $count ) {
					$args = Array( 'numberposts' => -1, 'post_status' => 'publish', 'fields' => 'ids', 'post_type' => $post_type, 'status' => $status );
					if ( $post_type == 'attachment' )
						$args['status'] = 'inherit';
					$args = $this->fetch_videos_only( $args );
					$q = new WP_Query( $args );
					$counts[$post_type] = $q->found_posts;
				}
			}
			return apply_filters( "{$this->prefix}count_videos_only", $counts );
		}

		/**
		 * Parse the content to get all video links.
		 *
		 * @param string $content The content of the post.
		 */
		private function get_video_links( $content, $get_local_video_desc = false ) {
			$links = array();
			if ( empty( $content ) ) {
				return $links;
			}

			// this array will store the links that are not embeddable e.g. videos produced through the [video] shortcode.
			$non_embed_links	= array();

			$dom_document = new DOMDocument();
			@$dom_document->loadHTML( $content );
			$dom_xpath = new DOMXpath( $dom_document );
			$iframes = $dom_xpath->query( "//iframe" );
			$embeds = $dom_xpath->query( "//embed" );
			$anchors = $dom_xpath->query( "//a" );
			$scripts = $dom_xpath->query( "//script" );
			$videos = $dom_xpath->query( "//video/a" );
			if (!is_null( $iframes ) && $iframes->length ) {
				foreach ( $iframes as $iframe ) {
					if ( $iframe->hasAttributes() ) {
						$attributes = $iframe->attributes;
						if ( !is_null( $attributes ) ) {

				  foreach ( $attributes as $index=>$attr ){
								if ( $attr->name == 'src' ) {
									$links[]        = $attr->value;
								}
							}
						}
					}
				}
			}
			if (!is_null( $embeds ) && $embeds->length ) {
				foreach ( $embeds as $embed ) {
					if ( $embed->hasAttributes() ) {
						$attributes = $embed->attributes;
						if ( !is_null( $attributes ) ){
							foreach ( $attributes as $index=>$attr ){
								if ( $attr->name == 'src' ) {
									$links[]        = $attr->value;
								}
							}
						}
					 }
				}
			}
			if (!is_null( $anchors ) && $anchors->length ) {
				foreach ( $anchors as $anchor ) {
					if ( $anchor->hasAttributes() ) {
						$attributes = $anchor->attributes;
						if ( !is_null( $attributes ) ){
							foreach ( $attributes as $index=>$attr ){
								if ( $attr->name == 'href' ) {
									$links[]        = $attr->value;
								}
							}
						}
					 }
				}
			}
			if (!is_null( $scripts ) && $scripts->length ) {
				foreach ( $scripts as $script ) {
					if ( $script->hasAttributes() ) {
						$attributes = $script->attributes;
						if ( !is_null( $attributes ) ){
							foreach ( $attributes as $index=>$attr ){
								if ( $attr->name == 'src' ) {
									$links[]        = $attr->value;
								}
							}
						}
					 }
				}
			}

			if (!is_null( $videos ) && $videos->length ) {
				foreach ( $videos as $video ) {
					if ( $video->hasAttributes() ) {
						$attributes = $video->attributes;
						if ( !is_null( $attributes ) ){
							foreach ( $attributes as $index=>$attr ){
								if ( $attr->name == 'href' ) {
									$links[]        = $attr->value;
									$non_embed_links[] = aiosp_common::absolutize_url( $attr->value );
								}
							}
						}
					 }
				}
			}

			if ( $get_local_video_desc ) {
				$this->video_descriptions = $this->get_video_descriptions( $non_embed_links );
			}

			$this->clean_video_links( $links );
			$links = $this->filter_links( $links );

			return $links;
		}

		/**
		 * Returns all Facebook & Flickr links and removes all other links.
		 * 
		 * @since 3.3.0
		 *
		 * @param array $links Links that have to be filtered.
		 * @return array  $links Filtered links.
		 */
		private function filter_links( $links ) {

			if ( ! is_admin() ) {
				return $links;
			}

			/**
			 * Allows users to change the Regex pattern that detects supported video links. 
			 * 
			 * The remaining links that are passed on are used for oEmbed discovery.
			 * 
			 * @since 3.3.0
			 * 
			 * @param string The Regex pattern.
			 */
			$search_pattern = apply_filters( 'aioseop_pro_supported_video_links', '#.*facebook.com/.*|.*flickr.com/.*#' ) ;

			return preg_grep( $search_pattern, $links );
		}

        /** run supported shortcodes in the content **/
		private function run_shortcodes( $content ) {
			
			/**
			 * Allows users to add additional support for video embed shortcodes.
			 * 
			 * @since 2.5.3
			 * 
			 * @param array Contains all supported shortcode tags.
			 */
			$supported	= apply_filters( 'aioseop_video_shortcodes', array( 'video' ) );
			if ( empty( $supported ) || ! is_array( $supported ) ) {
				return $content;
			}

			// let's collect all the shortcodes that exist in the content.
			$pattern	= get_shortcode_regex();
			$shortcodes	= array();
			if ( preg_match_all( '/'. $pattern .'/s', $content , $matches ) && array_key_exists( 2, $matches ) ) {
				$shortcodes	= array_unique( $matches[2] );
			}

			if ( empty( $shortcodes ) || ! is_array( $shortcodes ) ) {
				return $content;
			}

			// remove the shortcodes that are not supported.
			$not_supported	= array_diff( $shortcodes, $supported );
			if ( $not_supported ) {
				foreach ( $not_supported as $code ) {
					remove_shortcode( $code );
				}
			}

			// run the shortcodes that are supported.
			return do_shortcode( $content );
		}

		function parse_video_opts( $data, $return_single = false ) {
            $opts   = array();

			if ( empty( $data ) ) {
				return $opts;
			}

			$data = (array) $data;

			$links = array();

			if ( isset( $data['html'] ) ) {
				$links = $this->get_video_links( $data['html'], true );
			}

			if ( $links ) {
				foreach ( $links as $index => $link ) {
					$parse_url = parse_url( str_replace( ':////', '://', esc_url_raw( $link ) ) );
					if ( empty( $parse_url['scheme'] ) ) {
						$parse_url['scheme']    = 'http';
						$link                   = str_replace( ':////', '://', esc_url_raw( $this->unparse_url( $parse_url ) ) );
					}

					$query_params = array();
					parse_str( parse_url( $link, PHP_URL_QUERY ), $query_params );

					$opt            = array();
					foreach( self::$_fields as $k => $v ) {
						if ( ! empty( $query_params[$k] ) ) {
							$opt[$v] = ent2ncr( esc_attr( $query_params[$k] ) );
						} elseif ( ! empty( $data[$k] ) ) {
							$opt[$v] = ent2ncr( esc_attr( $data[$k] ) );
						}
					}

					$opt['video:player_loc'] = esc_url( $link );

					if ( empty( $opt['video:description'] ) ) {
						$opt['video:description'] = "Video ";
						if ( ! empty($opt['video:title']) ) {
							$opt['video:description'] .= $opt['video:title'];
						}
						if ( ! empty( $opt['video:uploader'] ) ) {
							$opt['video:description'] .= ' by ' . $opt['video:uploader'];
						}
					}

					if ( ! empty ( $data['id'] ) && ! empty( $opt['video:player_loc'] ) ) {
						$this->oembed_discovery( $link, $opt['video:player_loc'], null, $data['id'] );
					}

					if ( ! empty( $opt['video:player_loc'] ) && empty( $opt['video:thumbnail_loc'] ) ) {
						$this->get_additional_data( $opt );
					}

					if ( in_array( $link, array_keys( $this->video_descriptions ) ) ) {
						// videos that are uploaded do not have thumbnails. So we use a custom field, if its value has been provided by the user.
						if ( empty( $opt['video:thumbnail_loc'] ) && ! empty( $data['id'] ) ) {
							$thumbnail = get_post_meta( $data['id'], 'aioseop_video_thumbnail', true );
							$opt['video:thumbnail_loc'] = apply_filters( $this->prefix . 'thumbnail', $thumbnail, $data['id'], $opt );
						}

						if ( ! empty( $this->video_descriptions[ $link ] ) ) {
							$opt = array_merge( $opt, $this->video_descriptions[ $link ] );
						}

						if ( empty( $opt['video:title'] ) ) {
							$opt['video:title'] = $opt['video:description'];
						}

						$opt['custom']	= array( 'custom' => $this->order_sitemap_fields( $opt ) );
					}

					$opt = $this->order_sitemap_fields( $opt );

					if ( $return_single ) {
						$opts   = $opt;
					} else {
						$opts[] = $opt;
					}
				}
			} else if ( isset( $data['custom'] ) && ! empty( $data['custom'] ) ) {
				$opts[]	= $data['custom'];
			}

            return apply_filters( "{$this->prefix}add_videos", $opts );
		}

		/**
		 * Tries to get the description of locally uploaded videos and, if they are not local, just assumes it to be blank.
		 * NOTE: We are going to use parse_url and not wp_parse_url because we are going to correct these URLs if they are malformed.
		 *
		 * @param array $non_embed_links Array of video URLs.
		 *
		 * @return array URL => array of video sitemap fields with their values.
		 */
		private function get_video_descriptions( $non_embed_links ) {
			$non_embed_links = array_unique( array_filter( $non_embed_links ) );
			$map = array();
			$wp_host    = parse_url( home_url(), PHP_URL_HOST );
			foreach ( $non_embed_links as $url ) {
				$url = aiosp_common::absolutize_url( $url );
				$desc = '';
				$host = parse_url( $url, PHP_URL_HOST );
				if ( $host === $wp_host ) {
					// this is a local video.
					$desc = $this->get_local_video_description( $url );
				}
				$map[ $url ] = $desc;
			}
			return $map;
		}

		/**
		 * Gets the description of locally uploaded videos.
		 *
		 * @param string $url The URL of the video.
		 *
		 * @return array Array of video sitemap fields with their values.
		 */
		private function get_local_video_description( $url ) {
			global $wpdb;
			$row = $wpdb->get_row( $wpdb->prepare( "SELECT post_title, post_content FROM $wpdb->posts WHERE guid = %s and post_type = %s", $url, 'attachment' ), ARRAY_A );

			$attributes = array();

			if ( $row ) {
				$title = $row['post_title'];
				$desc = $row['post_content'];

				if ( ! empty( $title ) ) {
					$attributes['video:title'] = $title;
					$attributes['video:description'] = $title;
				}
				if ( ! empty( $desc ) ) {
					$attributes['video:description'] = $desc;
					if ( empty( $attributes['video:title'] ) ) {
						$attributes['video:title'] = $desc;
					}
				}
			}
			return $attributes;
		}

		/**
		 * Correctly order the sitemap fields so that it conforms to the schema.
		 *
		 * @param array $opt The array of values.
		 *
		 * @return array
		 */
		private function order_sitemap_fields( $opt ) {
			$order = array_values( self::$_fields );
			$ordered = array();
			foreach ( $order as $field ) {
				if ( isset( $opt[ $field ] ) ) {
					$ordered[ $field ] = $opt[ $field ];
				}

				if ( ! empty( $ordered[ $field ] ) && isset( self::$_field_types[ $field ] ) ) {
					switch ( self::$_field_types[ $field ] ) {
						case 'int':
							$ordered[ $field ] = intval( $ordered[ $field ] );
							break;
					}
				}
			}

			if ( isset( $opt['custom'] ) ) {
				$ordered['custom'] = $opt['custom'];
			}

			return $ordered;
		}
		
		/**
		 * Cleans up all found URLs and filters out duplicate video entries.
		 *
		 * @since 2.4.16
		 * 
		 * @param array $links Contains all found URLs (reference!).
		 * @return void
		 */
		private function clean_video_links( &$links ) {
            foreach ( $links as &$link ) {
                $link           = urldecode( $link );
                if ( strpos( $link, 'facebook.com' ) !== false ) {
                    if ( strpos( $link, '/videos/' ) !== false ) {
                        // the url can be of type 
                        // https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/facebook/videos/10155214512696729/&show_text=0&width=560
                        // OR
                        // https://www.facebook.com/facebook/videos/10155214512696729/
                        $args   = explode( '/', $link );
                        $args   = array_filter( $args );
                        // the video id is usually after /videos/
                        $index  = array_search( 'videos', $args );
                        if ( $index !== false ) {
                            $id     = $args[ $index + 1 ];
                            if ( ! is_numeric( $id ) ) {
                                $link   = null;
                            } else {
                                // change the link so that it becomes a definitive video link
                                $link   = "https://www.facebook.com/facebook/videos/$id";
                            }
                        } else {
                            $link   = null;
                        }
                    } else {
                        $link   = null;
                    }
                } else {
					// if the URL does not begin with a scheme and instead begins with //, it will be rejected. So let's correct it.
					if ( strpos( $link, 'http' ) !== 0 && strpos( $link, '//' ) === 0) {
						$link	= 'http:' . $link;
					}

					$link = aiosp_common::absolutize_url( $link );
				}

				// ignore js files: some vidoes, e.g. wordpress.tv embed the iframe as well as the script so the video is detected twice
				if ( strpos( $link, '.js' ) !== false ) {
					// make sure that this is indeed a url that refers to the js file and not a url that might have the string .js
					$path	= parse_url( $link, PHP_URL_PATH );
					if ( strpos( $path, '.js' ) === ( strlen( $path ) - 3 ) ) {
						$link		= null;
					}
				}

				if ( ! filter_var( $link, FILTER_VALIDATE_URL ) ) {
					$link = null;
				}
            }

			$links  = array_unique( array_filter( $links ) );
        }

        /** if certain attributes (such as thumnbnail) have not been provided, try a service-specific method to fetch them **/
        function get_additional_data( &$opt ) {
            $link   = $opt['video:player_loc'];
            if ( strpos( $link, 'facebook.com' ) !== false ) {
                if ( strpos( $link, '/videos/' ) !== false ) {
                    $args   = explode( '/', $link );
                    $args   = array_filter( $args );
                    // the video id is usually after /videos/
                    $index  = array_search( 'videos', $args );
                    if ( $index !== false ) {
                        $id     = $args[ $index + 1 ];
                        if ( is_numeric( $id ) ) {
                            $opt['video:title'] = $id;
                            $opt['video:thumbnail_loc'] = "http://graph.facebook.com/$id/picture";
                        }
                    }
                }
            }
        }

		function oembed_discover_url( $url, $id ) {
			// only enable this if requests time-out on development instance.
			//set_time_limit(0);
			$data = array();
			if ( !empty( $url ) ) {
                $url        = $this->massage_oembed_url( $url );
				$parse_url = parse_url( str_replace( ':////', '://', esc_url_raw( $url ) ) );
				if ( empty( $parse_url['scheme'] ) ) $parse_url['scheme'] = 'http';
				$url = $this->unparse_url( $parse_url );

				include_once( ABSPATH . 'wp-includes/class-oembed.php' );
				$wp_oembed = self::$_wp_oembed;
				$provider = false;
                foreach ( $wp_oembed->providers as $matchmask => $d ) {
                        list( $providerurl, $regex ) = $d;
                        if ( !$regex ) {
                                $matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
                                $matchmask = preg_replace( '|^#http\\\://|', '#https?\://', $matchmask );
                        }
                        if ( preg_match( $matchmask, $url ) ) {
                                $provider = str_replace( '{format}', 'json', $providerurl ); // JSON is easier to deal with than XML
                                break;
                        }
                }
				if ( empty( $provider ) ) {
					$provider = $wp_oembed->discover( $url );
                }

				if ( !empty( $provider ) ) {
					$data = $wp_oembed->fetch( $provider, $url, Array( 'discover' => true ) );
					if ( $data && 'video' !== $data->type ) {
						// Exclude everything but video embeds.
						$data = array();
					}

                    // if its a wordpress.tv url, it will resolve into a videopress.com video
                    // but the oEmbed does not give a thumnbnail so we have to manipulate this to get it to parse as a videopress video instead
                    if ( $data && strpos( $url, 'wordpress.tv' ) !== false && strpos( $data->html, 'videopress.com' ) !== false ) {
                        $data   = $this->parse_video_opts( array( 'id' => $id, 'html' => $data->html ) );
                        if ( is_array( $data ) && isset( $data[0]['video:player_loc'] ) && strpos( $data[0]['video:player_loc'], 'videopress.com' ) !== false ) {
                            $data   = $this->oembed_discover_url( $data[0]['video:player_loc'], $id );
                        }
                    }
                }
			}
			return $data;
		}

        /** do we need to change the url in any way so that the oEmbed provider can get the correct information? **/
        function massage_oembed_url( $url ) {
            $providers  = array(
                // videopress iframe embeds have a url structure like /embed/ but the oEmbed endpoint only recognizes /v/ type urls
                'videopress.com/embed/' => 'videopress.com/v/',
                // funnyordie iframe embeds have a url structure like /embed/ but the oEmbed endpoint only recognizes /videos/ type urls
                'funnyordie.com/embed/' => 'funnyordie.com/videos/',
                // viddler iframe embeds have a url structure like /embed/ but the oEmbed endpoint only recognizes /v/ type urls
                'viddler.com/embed/' => 'viddler.com/v/',
                // youtube iframe embeds have a url structure like /embed/ but the oEmbed endpoint only recognizes /watch?v= type urls
                'youtube.com/embed/' => 'youtube.com/watch?v=',
            );

            foreach( $providers as $orig => $new ) {
                if ( strpos( $url, $orig ) !== false ) {
                    // if the target URL has the structure of a query string then, to ensure the final URL does not look like www.xxx.com?a=b?c=d
                    // lets change the existing query string to start with an & instead so that it reads www.xxx.com?a=b&c=d
                    if ( strpos( $new, '?' ) !== false ) {
                        $url    = str_replace( '?', '&', $url );
                    }
                    return str_replace( $orig, $new, $url );
                }
            }
            return $url;
        }

		/** oEmbed discovery - save in post meta **/
		function oembed_discovery( $html, $url, $c, $id, $data=null ) {
			$opts = get_post_meta( $id, '_aioseop_oembed_info', true );
			if ( empty( $opts ) ) $opts = Array();
			if ( !empty( $opts[$url] ) ) return $html;

            // if we have custom parsed the HTML and determined all attributes ourselves, don't let ombed do it
            if ( is_null( $data ) || ! isset( $data['custom'] ) ) {
    			$info = $this->oembed_discover_url( $url, $id );
            } else {
                $info   = (object) $data['custom'];
            }
			if ( !empty( $info ) ) {
				$opts[$url] = $info;
				update_post_meta( $id, '_aioseop_oembed_info', $opts );
			}
			return $html;
		}

		function oembed_cache( $post ) {
			global $wp_embed;
			delete_post_meta( $post->ID, '_aioseop_oembed_info' );
            $contents   = $this->get_post_content( $post->ID, $post );
			if ( ! empty( $contents ) ) {
				$wp_embed->post_ID = $post->ID;
				$wp_embed->usecache = false;
				$content = $wp_embed->run_shortcode( $contents );
				$wp_embed->autoembed( $content );
				$wp_embed->usecache = true;
			}
		}

		function scan_post( $id ) {
			$post_obj = get_post( $id );

			// the global post object is required by the filter aioseop_embed_handler_html.
			if ( defined( 'AIOSEOP_UNIT_TESTING' ) ) {
				global $post;
				$post = $post_obj;
			}

			// scanning a post with multiple videos might take a lot of time.
			set_time_limit(0);
			add_filter( 'embed_oembed_html', array( $this, 'oembed_discovery' ), 10, 4 );
			add_filter( 'embed_handler_html', array( 'AIO_ProGeneral', 'aioseop_embed_handler_html' ), 10, 3 );

			$this->oembed_cache( $post_obj );
			if ( ! empty( $post_obj ) ) {
				$html   = $this->get_post_content( $id, $post_obj );
				$parse  = $this->parse_video_opts( array( 'id' => $id, 'html' => $html ) ); // try to detect manual embed codes
				if ( ! empty( $parse ) ) {
					foreach( $parse as $datum ) {
						if ( ! empty( $datum['video:player_loc'] ) ) {
							$this->oembed_discovery( $html, $datum['video:player_loc'], null, $id, $datum );
						}
					}
				}
			}
		}


        /** get the post content + excerpt + (if enabled) all the post meta data **/
        function get_post_content( $id, $post = null ) {
            if ( ! $post ) {
                $post   = get_post( $id );
            }

			$content	= $post->post_content;
            if ( ! empty( $post->post_excerpt ) ) {
				$content	.= ' <br /> ' . $post->post_excerpt;
			}
			// enclose tags with <p> tags after changing all newlines to <br>s
			$content	.= '<p>' . str_replace( '<br />', '</p><p>', nl2br( $content ) ) . '</p>';
			// get the non-tag content and enclose each word with <p> tags (for the case where embeddable content is provided in the text)
			$content	.= '<p>' . implode( '</p><p>', explode( ' ', strip_tags( $content ) ) ) . '</p>';
            if ( $this->option_isset( 'custom_fields' ) ) {
                $meta       = get_post_meta( $id );
                if ( $meta ) {
                    foreach ( $meta as $key => $value ) {
                        // ignore the keys that start with _wp and _oembed
                        if ( ! ( strpos( $key, '_wp' ) === 0 || strpos( $key, '_oembed' ) === 0 ) ) {
                            $content    .= ' <p>' . html_entity_decode( $value[0] ) . '</p>';
                        }
                    }
                }
            }
            return $this->run_shortcodes( $content );
        }

	}
}

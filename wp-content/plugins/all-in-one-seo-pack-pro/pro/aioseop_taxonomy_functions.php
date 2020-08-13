<?php
/**
 * Functions specific to taxonomies.
 *
 * @package All-in-One-SEO-Pack
 */

if ( ! function_exists( 'aioseop_taxonomy_columns' ) ) {
	/**
	 * Shows the columns in the supported taxonomies.
	 *
	 * @since 3.0
	 *
	 * @param array $columns The array of columns.
	 *
	 * @return array The array of columns.
	 */
	function aioseop_taxonomy_columns( $columns ) {
		global $aioseop_options;
		$columns['aioseop_title'] = __( 'SEO Title', 'all-in-one-seo-pack' );
		$columns['aioseop_desc']  = __( 'SEO Description', 'all-in-one-seo-pack' );
		if ( empty( $aioseop_options['aiosp_togglekeywords'] ) ) {
			$columns['aioseop_keywords'] = __( 'SEO Keywords', 'all-in-one-seo-pack' );
		}
		return $columns;
	}
}


if ( ! function_exists( 'aioseop_taxonomy_manage_columns' ) ) {
	/**
	 * Shows the column values in the supported taxonomies.
	 *
	 * @since 3.0
	 *
	 * @param string $out The output to display.
	 * @param string $column_name The name of the column.
	 * @param int $id The column id.
	 *
	 * @return string The column value.
	 */
	function aioseop_taxonomy_manage_columns( $out, $column_name, $id ) {
		switch ( $column_name ) {
			case 'aioseop_title':
				echo esc_html( get_term_meta( $id, '_aioseop_title', true ) );
				break;
			case 'aioseop_desc':
				echo esc_html( get_term_meta( $id, '_aioseop_description', true ) );
				break;
			case 'aioseop_keywords':
				echo esc_html( get_term_meta( $id, '_aioseop_keywords', true ) );
				break;
		}
		return $out;
	}
}

if ( ! function_exists( 'aioseop_taxonomy_post_register' ) ) {
	/**
	 * Fired once a taxonomy is registered and registers the hooks for showing the columns.
	 *
	 * @since 3.0
	 *
	 * @param string $taxonomy The taxonomy slug.
	 */
	function aioseop_taxonomy_post_register( $taxonomy ) {
		// check if this taxonomy is enabled for SEO.
		global $aioseop_options;

		if ( ! empty( $aioseop_options['aiosp_taxactive'] ) && in_array( $taxonomy, $aioseop_options['aiosp_taxactive'], true ) ) {
			add_filter( "manage_{$taxonomy}_custom_column", 'aioseop_taxonomy_manage_columns', 10, 3 );
			add_filter( "manage_edit-{$taxonomy}_columns", 'aioseop_taxonomy_columns', 10, 1 );
		}
	}
}

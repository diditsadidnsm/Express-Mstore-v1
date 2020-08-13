<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function kyma_blocks_cgb_block_assets() { // phpcs:ignore
	// Styles.
	wp_enqueue_style(
		'kyma_blocks-cgb-style-css', // Handle.
		get_template_directory_uri().'/inc/plugins/kyma-blocks/dist/blocks.style.build.css', // Block style CSS.
		array( 'wp-editor' ) // Dependency to include the CSS after it.
	);
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'kyma_blocks_cgb_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function kyma_blocks_cgb_editor_assets() { // phpcs:ignore


	// Scripts.
	wp_enqueue_script(
		'kyma_blocks-cgb-block-js', // Handle.
		get_template_directory_uri().'/inc/plugins/kyma-blocks/dist/blocks.build.js', // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'), // Dependencies, defined above.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'kyma_blocks-cgb-block-editor-css', // Handle.
		get_template_directory_uri().'/inc/plugins/kyma-blocks/dist/blocks.editor.build.css', // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
	);

	wp_enqueue_style(
		'kyma_blocks-cgb-block-fontawesome', // Handle.
		get_template_directory_uri().'/css/all.css', // Font Awesome for social media icons.
		array(),
		'5.6.3'
	);

}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'kyma_blocks_cgb_editor_assets' );

if ( !function_exists( 'kyma_block_category' ) ) {
    /**
     * Add our custom block category for kyma blocks.
     *
     * @since 0.6
     */
    function kyma_block_category( $categories, $post )
    {
        return array_merge( $categories, array( array(
            'slug'  => 'kyma-blocks',
            'title' => __( 'Kyma - Gutenberg Blocks', 'kyma' ),
        ) ) );
    }
    
    add_filter(
        'block_categories',
        'kyma_block_category',
        10,
        2
    );
}

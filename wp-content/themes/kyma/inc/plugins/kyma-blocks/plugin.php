<?php
/**
 * Plugin Name: Kyma blocks
 * Plugin URI: https://github.com/ahmadawais/create-guten-block/
 * Description: Kyma blocks services, pricing tables, callout and many more.
 * Author: WebHunt Infotech
 * Author URI: https://webhuntinfotech.com/
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once dirname( __FILE__ ) . '/src/init.php';

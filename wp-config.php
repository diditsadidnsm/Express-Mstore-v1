<?php
define('WP_CACHE', true);

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'multijakarta_wp475' );

/** MySQL database username */
define( 'DB_USER', 'multijakarta_wp475' );

/** MySQL database password */
define( 'DB_PASSWORD', '3S67.S[bp8' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'lbyqyuettyp0vklvfqzdfusgcyngrrvyljqbrcslj6z3m1pv4qyqimyuyytresdr' );
define( 'SECURE_AUTH_KEY',  'wydfc9l8i7mjyrtgadhfnt9753f3by7jvxlly5zi0qlbdieq5wvzo4frj67ar6po' );
define( 'LOGGED_IN_KEY',    'mydsweqxlpvg0f8zjshdlrez97arwrhrwg93uhq76wijat2cdggnk7tffnjjlfcl' );
define( 'NONCE_KEY',        'skfbvj7ggoxluoeu5cylgn7scdmhw1ltyfyhsox7sjm1bboqhihjegsg7dpfxn8e' );
define( 'AUTH_SALT',        'dk6hepcrn5qyh9mrsoku2b53la4tmxq88yo524rbxpczkhgmvmu9k1gtxbngvh6x' );
define( 'SECURE_AUTH_SALT', 'lkb9fjmwtgvrbkcvbwuhjx9qmmtuufiandxj2ezrgfsfk8gr3uukymox0isutqfs' );
define( 'LOGGED_IN_SALT',   'uyurierstsaproag0ual75p1v90vpdlds2jqt6ghpyh86434sttxyqbm8ulaqegr' );
define( 'NONCE_SALT',       'db1d4a3zb20xmslbsawyx106zcts7ghpk0qgccokec2choufybufp23ich3vgemx' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpkb_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

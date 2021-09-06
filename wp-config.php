<?php
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //

include 'wp-content/themes/proapp/vendor/autoload.php';

/** The name of the database for WordPress */
define( 'DB_NAME', 'vs_phienchoyeuthuong' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'dvD#154hfknd!.' );

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
define( 'AUTH_KEY',         'u_hlr>oXKt=~dIAc*G``gl_*89gM~QDe9SClc4:LBR23:/jZmE.v8gqm_MS{@[rW' );
define( 'SECURE_AUTH_KEY',  'O6xtxPwP}0 f#^o9C1r]&=hD=VC;Y&<uKR1@wcy$z56TN.:@L,&tl_(l1r>]J|Wi' );
define( 'LOGGED_IN_KEY',    '#on7oi(_H:~BTZZV_&OPS!/04KZ@::A?dZtK->RkS2%3:6]RIdm,uJ?O<U,RP U/' );
define( 'NONCE_KEY',        '=tE2J4hBK!`IV3j:{MkT7*u_S6|B@I]Kx=~J[0|)7/HZ}z<}HNcR-[fM5WeJ6eqL' );
define( 'AUTH_SALT',        'xK]s;0=fQ!YQ[M^}it,Te|SV)/=TL)2`mNDve)xO7JcWM0t}rXzr$xt2*paVI4fN' );
define( 'SECURE_AUTH_SALT', 'z#1(-*c-%`}{7Zrd1~Kx1p]DiR7]|@fZ@g$R.,[:mY|Frb?{_(xR@:o;3sb`(g9*' );
define( 'LOGGED_IN_SALT',   'DFIa%O@<NIn{t5(9LNA(}.Q?;|yZx]BoUtlYQ[&Y$~}Q|a6eG.6t!@`Pl_c%eer7' );
define( 'NONCE_SALT',       ',03xG~, V|=bBbp.Vi]pF!|o$9mwZ|wz p7P[xCnFQ?XGMG.>a6>A+Mg1cRl.Ke8' );
define( 'DDL_DOMAIN',       'a09a5585a352509f21261a137b37bfa4' );
define( 'DDL_INTERNAL',     true );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'se_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define('ALLOW_UNFILTERED_UPLOADS', true);
define('API_URL', 'http://103.57.221.135:8080/graphql');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

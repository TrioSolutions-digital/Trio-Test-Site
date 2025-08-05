<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '/Fqt7|.hky.IoP/FY?=`:xxi~jN|OPBSQNNDu. Q)eB5a2dzQoH*8 cjHzTDA)2E' );
define( 'SECURE_AUTH_KEY',   '_NYb?_!j%fNqBx]Hyyr>,WJA#F*%edF.f8uDf$u}B1?zF=uP;En|%BFA[<k*vt&f' );
define( 'LOGGED_IN_KEY',     '1%Z8&=*AV^N{@/{ 5,A_cA]>>!=QT`E g>K3B3|S$E|L6(n#=&lQ=ZwH1h@w*a_~' );
define( 'NONCE_KEY',         'Ti}gW7)~m=a}!e^i{_ieK?xdlx=z^RZ0{D`)NpR:&Nws~e$F nx:q>,o&)PtTVr5' );
define( 'AUTH_SALT',         '0/{)|I&Q4[sDC22+8X!&sAm{}x 40)sdD6aHX~31h2r}61S:]%%jlng,(xOA,ceB' );
define( 'SECURE_AUTH_SALT',  '|a4rMe(B:kUhv{%Nr0>0!:kn7zM^9&1CNv8bMA2&-eDNW;$>JVbohj7]x>! P_,)' );
define( 'LOGGED_IN_SALT',    'a+2;RC{gMI](<QjW#|GC>j2:TYdpf&bQ#-h!sN&X2G#UQIv)6.YP-A_TV5?MrG]R' );
define( 'NONCE_SALT',        ')4S:9emi7L^.,ayW6<KOO>^5EYY+ J{)J!J~uEa_V.P@fB<,Hd1D5f2ZP&n3ilSC' );
define( 'WP_CACHE_KEY_SALT', 'mj@JdD{df?Z;% s!PlG^*UP[jW%!)6n.P`z_uZ8(NeLXF~b_a2^9FA.B~EFT: 2`' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_ssyv2ssltc_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

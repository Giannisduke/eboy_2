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
/** The name of the database for WordPress */
define( 'DB_NAME', 'intergra_my_site' );

/** MySQL database username */
define( 'DB_USER', 'intergra_my_site' );

/** MySQL database password */
define( 'DB_PASSWORD', 'renos1' );

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
define( 'AUTH_KEY',         'w.l6jfw09l+S Ww[L` RFFf;lWEYTASEDAaS7gUsekS=ML*,$+G/.Sxq`(U[$<{i' );
define( 'SECURE_AUTH_KEY',  '+?W;J5A!fU&]/;&~c)rvVW:Q!.43(Tr_j:wZI*R!X)[N9+%Xo)f!z}*-ak[*;<[c' );
define( 'LOGGED_IN_KEY',    'tuE7<os~8@s2xV+2L*q:nR8!RLKO`6UK9=l?%pzG<=0c.,>(Tl,R{YT@]2]%%fKH' );
define( 'NONCE_KEY',        ';KV[,hj`oO!MCkd;G,(fjtX-=:%+%4r43yB%-(<(tB}cK(mVnHPX^h Rd 5^x5[1' );
define( 'AUTH_SALT',        'TF,<6Q^=-2;uBeW#i)U7It.sGHoev1l)+=T1iQ);nP#Ala*I[AQ0EqDIRxK{.yll' );
define( 'SECURE_AUTH_SALT', '%GP5p0smvQ600ye*fpCGa3*[R4&>jhhwN{Eo!QU;<^-Z*JyBkZb}+Vy,]=wF/P$@' );
define( 'LOGGED_IN_SALT',   '[EtqKEmnj~k`1S&sb{Ey.(=,s9/!-eML69Jmtfl<TJHJw}b=%B/)+FQMJ_Yi9)eq' );
define( 'NONCE_SALT',       '/+J`8Q)D[^U%$WTfjO<t|aJ|FqF*~r6Fp}rV~t(B!ce*fq8SbGSyuGA2rU;AJG>}' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

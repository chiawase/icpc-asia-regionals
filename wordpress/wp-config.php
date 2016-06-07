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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'icpc-regionals');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'HXnx4a(kd`KZZ-`pUmGJHZ@@myWn/r3R~/#YIShU>6;Lv2{-z^<M&|=PdN=|</FL');
define('SECURE_AUTH_KEY',  'dT#){hAd#enepq7)<+LPm3A4|P}TT=TR-pLs8V-sMWTuk7}vAL~/Zf4S!Pl4RM[E');
define('LOGGED_IN_KEY',    'G)+]mm*O?OWKlhlD`A`oZ`Zp@+eQ.xRfV.U~-5SMR[9vh`np-H6Jhyu)x4d$R7=I');
define('NONCE_KEY',        'o.ob#z(@dzf9G}ehn$?@d>HA#|,k#mfUJ=wwwx_Vj7@G[t+|eG.{&H0aWI[vNgT*');
define('AUTH_SALT',        '7YibyEhT^3Ol;TVeV]KG,n)SkTV*aS+{ErC+1?~$T))/w#TCq&3S.a=|Lc2d3=cE');
define('SECURE_AUTH_SALT', 'kS4.gwSlhGzf(<Lr}h]/=?heaZ-Eph2|~D.2V ~ktd8Uc+I:l=mG7x}s8JbKG|5_');
define('LOGGED_IN_SALT',   'KxuC2/t~-V[V@l=!0G]gmy<)|*.eWz+[:7XPD0f4k`_eMG?-+p#:&U]LfN_+P47m');
define('NONCE_SALT',       'j/p#6)>cV<}THNpxR|VTFT]| B]tVSMn,S._nh$Mksd*OoV&imf~E]c_)oQHgUWe');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
  define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

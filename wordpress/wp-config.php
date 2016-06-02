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
define('AUTH_KEY',         '+0soyNdM:K?.m@ne<#zN9%+UcU`d1F_%tuvJb.Jk]Wzt{{QMGZgnXH?X]BEeAKD~');
define('SECURE_AUTH_KEY',  '0Z&lOX(ZVF>nFk@<WU!`{uzQ2E?qyn8,y8,jE&&r_al+~iZWUA$P>C<h^PK%Du`_');
define('LOGGED_IN_KEY',    'T-`L@zr5-G|6g,.G&N+g*TxY]-B`sPuZ=v54b+N>+Ur-Ism#SJ-.Rp+~AV#PL2,;');
define('NONCE_KEY',        'IosoB&Ma5I m*V8*ni1>(Z7T?8v)jx?D8T^JFOJi|hlUb&<egep F81bWx?qCnf+');
define('AUTH_SALT',        'Ne)-p]5-IQh7]~01#i,WU<eh_7_dp][[-=,`7)uJl0WXk:pNp^3v&!csUh:,hpD1');
define('SECURE_AUTH_SALT', '9FKjrDj|~7BGDH/Ma*|<--0xUh.J{|zEB{jB[nY#8Shw2u;sIAp,7EJL#;Idpb;t');
define('LOGGED_IN_SALT',   'P6mt4QD|w+[w0%xQkhAU|H9|z9:p-,No{FzEtMzbvoZTPiIDPN^AdBLnN;V-K[%>');
define('NONCE_SALT',       'c=b!<X9ahFJ*4zQ#D`ps[`hP+fLK|R=NTWowuQ0<~]y;Am#|C|y#:CWGtRJKX<Lm');

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

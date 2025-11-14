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
define('DB_NAME', 'minhamimo');

/** MySQL database username */
define('DB_USER', 'minhamimo');

/** MySQL database password */
define('DB_PASSWORD', 'Mimimimimi123!');

/** MySQL hostname */
define('DB_HOST', 'minhamimo.mysql.dbaas.com.br');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'dIdKwmTO~1B2TT@rr<QJPzlrEG0cl19oP2&EG:{MOTy4kNH9bXYh9_2|pC3uB`l0');
define('SECURE_AUTH_KEY', '7{iuF^=*)zd3cGJK9NS#iUS3+Y<</1Kdt@7@l`G(FwhBGd6E|o~OzOtA r$>r8jF');
define('LOGGED_IN_KEY', '=!j+u<D{{fh:tXx8K%?VDsCQ/wq#[)h7<j4L4RQCi.HVNNAh1e:@wCqhRW#P)-K~');
define('NONCE_KEY', '>JX.31{9VX,~eyuG;E@g}rW$v](m:p%CHC.lRUuRZS;Po6+EP7`A #: TLc[lmXg');
define('AUTH_SALT', 'DUaE2QZh9/#_sE^CAMV8hYs/,3na+[yp6>gk+W/HoF)BS]%v.3j?E_FpYpd=m;Dt');
define('SECURE_AUTH_SALT', 'TLYZLUDKQw[hWb:Yi3R$hz/FjlgIumJ@t0nc Kx&hm;3H-JDpEo{/EBfQ}wN#qHK');
define('LOGGED_IN_SALT', '$*wE8:A;eTM]} ;lpEq6qk|dQgh~L}-%DaVY+qHx=a+,xf~tiBOs9*!hY-%`/i4V');
define('NONCE_SALT', '!.jN))x,T`.C/VK~]DM,^m^$(:]-J!Ud*qe^aNr&A>%~GjKsX<>,}<rWG##Y*R&j');
define('WP_CACHE_KEY_SALT', 'Tc80t-erm>E<K31QsUFj8cf~D+7|Bl|{Vq>IwZjzH&jay~}uV=TR:tGV,&_o2Pr$');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wordpress_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

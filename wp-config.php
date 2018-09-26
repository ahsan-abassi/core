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
define('DB_NAME', 'coreagency');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '8<kBJ2ZHE7MnS&(|2o{>~*%Lar-^{ERkdzrPTAj([jn4a%}#kG~VW{&IXkm[,E_L');
define('SECURE_AUTH_KEY',  'g]20AL{dwL;}9-ByShS&4Tw!6,cH!Rg..Lab~4$j((^:&Vj>O8Y<Xok$e3`{KF`A');
define('LOGGED_IN_KEY',    '4Dzg=(U_m}=QG]E>dgjb@MW5jX|h^cow}Ztn ot(dObEo];~Uf%C)Ycf+UGOhqPd');
define('NONCE_KEY',        'L@t(LX2dx4%|la*;D3iuQ|gesX,+^wP5_Wfk]P$%jA+WH_U[r/J+].Gq x>_X9>r');
define('AUTH_SALT',        'X@HVXKVMJt=hXI2IpBrV3VN>i&V_;vK#6}Dps#Vi(r244C]SGqy})lbX1EK4W.:s');
define('SECURE_AUTH_SALT', 'H}HtB.AxHC]]0rB2I-Jm?kZr0Q}|:+Yd{M<?RHd9E0+wC/{h]&YS%aUS.^[@~9O^');
define('LOGGED_IN_SALT',   '%wI% tjvgm]B$IKV(0`fos%1=gUSDKP$i1TB@P,WFLRZjII}_8m3_H?N=*+l&=x-');
define('NONCE_SALT',       'G7M7oNlc[T!LY^n=+cITi)Zy8%#?D/{4O*66+OM/ti&q)fv3aR^t=sOXy!6/ jDp');

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
define('ALLOW_UNFILTERED_UPLOADS', true);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


Creaping write....
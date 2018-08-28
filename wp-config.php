<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'dpg');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'dpg');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'dpg');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'HY;:YHoC<S$CDM^,t/$[#o`RV8_7Xp2^y9>zkf/)}&Li;,%N9Ow0<qs|Li/cPN6M');
define('SECURE_AUTH_KEY',  'Vig$Rs&eFg1X%Kf|.57O5VZ6A&GV!ga/aOc||+ua(k}gzCbE->-D7|c^}(iCvP}j');
define('LOGGED_IN_KEY',    'sS,{N8q>N)!|fwo.mFDCte}w #Vnyn^xt3&p8%49iSNxkZT-K&@aycNG<aj]&$-n');
define('NONCE_KEY',        '5o|;INcqu~|kW8*JC<9jNigYc+#MA111!!g]@UE2c3kaVL*xblA+d||&~}tPqKz;');
define('AUTH_SALT',        'xKQU?p>|%-}>-mol,Y`5aXkMY^Q.)w9a )wjh6X6ahC)y2#Ba%HzZIq*-PS_3P(e');
define('SECURE_AUTH_SALT', '>ivQ%[,Q/<Z9.r+}c38dZ[Jl*Vp)^VK~<*8rU8O_zmmSb$q/XJP]QyO,BqXsZ7@W');
define('LOGGED_IN_SALT',   'gdG4@E;EY-K@[5VvA3F5H tyf|} mW&}=;:,H<Xuw7E~:jb+sO$/4^)@riEul?mT');
define('NONCE_SALT',       'r0I.sEowB}$v]7rdSK@SSsn?chA]Al+lMdA?O=6~Rmuv0nt<G[Q~Q/I>aeMcWF&H');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'dpg_';

/**
 * Langue de localisation de WordPress, par défaut en Anglais.
 *
 * Modifiez cette valeur pour localiser WordPress. Un fichier MO correspondant
 * au langage choisi doit être installé dans le dossier wp-content/languages.
 * Par exemple, pour mettre en place une traduction française, mettez le fichier
 * fr_FR.mo dans wp-content/languages, et réglez l'option ci-dessous à "fr_FR".
 */
define('WPLANG', 'fr_FR');

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', true); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');
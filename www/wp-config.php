<?php



define( 'WP_IMPORTING', true );
define('WP_USE_EXT_MYSQL', false);

require_once (__DIR__.'/vendor/autoload.php');

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'db' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'admin' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'admin' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'mysql' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Xnv$0j@YKgSW*a43L27j10x2(EG|UFHxD-GtE8&+$7Voxu1dY0sO,-kxY3r41Uln' );
define( 'SECURE_AUTH_KEY',  'h>1+njG7&l^/;][!_Dy[X[9O)1~F,#C[nWjbxfp2d^^+O%}v[fYxSSQ:=$_5cgiz' );
define( 'LOGGED_IN_KEY',    'QwOG:zx-=A#hjQ,5Ouf%~Fq)|4l~%o>{xuo1jdPA79E`7L%|kQI;X)y;/})Sa&Q>' );
define( 'NONCE_KEY',        '9%c3=D[:ah6IJ)_oWoUExKpIHK=yAog8`or{&H_XS)zlA6M+[/>a,DfT;.ut^S3{' );
define( 'AUTH_SALT',        '{Wk-~>YJ:i%RPb|5M u~&Z2&DRdl[Q0B7G3f$Sz3dB$TN$P{s.FRm..!OJ8MwG$N' );
define( 'SECURE_AUTH_SALT', '|I rg+@70L_e5IcTfq{GkW)7*$?JgBu!?|$#$PcK%^daLj-I;#2m.oaJz7Y.c4Bh' );
define( 'LOGGED_IN_SALT',   'U%!/YB5R$R(Wc?xhKf)-y>}Df92o~/RA*VjK[Iq:0U>RL5;GRdZfE/;fb>9 kKx.' );
define( 'NONCE_SALT',       'ZlG!n:X>qufaOTxAz+nN6.Emrqo-Y| gSy{3e>SwV.AfLR|&?2TI0J;9`H1KnI)p' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */


// Enable WP_DEBUG mode
define( 'WP_DEBUG', true );

// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );

// Disable display of errors and warnings
define( 'WP_DEBUG_DISPLAY', true );

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', true );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';

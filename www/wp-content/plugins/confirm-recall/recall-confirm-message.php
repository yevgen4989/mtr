<?php
/**
 * Plugin Name: Recall Confirm Message
 * Description: Подтверждение обработки заявки обратного звонка
 * Author:      Eugene Pisotsky
 * Author URI:  https://eugene-pisotsky.website
 * Text Domain: recall-confirm-message
 * Domain Path: /lang
 * Version:     1.0
 */


// Quit, if now WP environment.
defined( 'ABSPATH' ) || exit;

define( 'APJ_PCM_VERSION', '1.0' );

define( 'APJ_PCM_REQUIRED_WP_VERSION', '4.5' );

define( 'APJ_PCM_PLUGIN', __FILE__ );

define( 'APJ_PCM_PLUGIN_NAME', 'Confirm Recall');

define( 'APJ_PCM_PLUGIN_PATH', 'recall-confirm-message.php');

define( 'APJ_PCM_MENU_SLUG', 'recall-confirm-message/core/admin/adminpage.php' );

define( 'APJ_PCM_DEFAULT_MESSAGE', 'Are you sure you want to Recall this now?');

define( 'APJ_PCM_OPT_NAME', 'apj_pcm_message');

define( 'APJ_PCM_OPT_ERR_NAME', 'apj_pcm_admin_error');

require_once plugin_dir_path(__FILE__) . 'core/apj-functions.php';

//Activate plugin
register_activation_hook(__FILE__, array('apjPCM\RecallConfirmMessage', 'activate'));

//Uninstall plugin
register_uninstall_hook(__FILE__, array('apjPCM\RecallConfirmMessage', 'uninstall'));

//Init hooks
\apjPCM\RecallConfirmMessage::initHooks();


?>

<?php
namespace apjPCM;
if (!defined('ABSPATH'))
{
    die('-1');
}
/**
 * @package Recall Confirm Message
 * @version 1.3
 * @since 1.0
 */
class RecallConfirmMessage
{
    /**
     * Plugin activation
     * @return void
     */
    public static function activate()
    {
        self::checkRequirements();
    }
    /**
     * Plugin uninstall
     * @return void
     */
    public static function uninstall()
    {
        self::apjuninstallplugin();
    }
    /**
     * Check plugin requirements
     * @return void
     */
    private static function checkRequirements()
    {
        /*
        delete_option(APJ_PCM_OPT_ERR_NAME);
        if (function_exists('has_blocks'))
        {
        $error = '<strong>' . sprintf(__('%s v%s requires Gutenberg Editor to be deactivate.', 'apjPCM') , APJ_PCM_PLUGIN_NAME, APJ_PCM_VERSION) . '</strong> ';
        update_option(APJ_PCM_OPT_ERR_NAME, $error);
        }
        */
    }
    /**
     * Uninstall plugin
     * @return void
     */
    private static function apjuninstallplugin()
    {
        delete_option(APJ_PCM_OPT_ERR_NAME);
        delete_option(APJ_PCM_OPT_NAME);
    }
    /**
     * Initialize WordPress hooks
     * @return void
     */
    public static function initHooks()
    {
//        //Admin notices
//        add_action('admin_notices', array(
//            __CLASS__,
//            'adminNotices'
//        ));
//        //Admin menu
//        add_action('admin_menu', array(
//            'apjPCM\RecallConfirmMessage',
//            'adminMenu'
//        ));

        //add_action( 'admin_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));

        add_action('admin_footer', array(
            __CLASS__,
            'APJConfirmRecall'
        ));


        add_filter("plugin_action_links", array(
            __CLASS__,
            'PluginActionLinks'
        ) , 1, 2);
        //Admin page
        $page = filter_input(INPUT_GET, 'page');
        if (!empty($page) && $page == APJ_PCM_MENU_SLUG)
        {
            add_filter('admin_footer_text', array(
                __CLASS__,
                'adminFooter'
            ));
        }
    }

    public static function enqueue_scripts()
    {
        wp_enqueue_script( 'jquery-ui-core');
        wp_enqueue_script( 'jquery-ui-dialog');
        wp_enqueue_style('wp-jquery-ui-dialog');


        if (get_current_screen()->id == 'recall'){
            $apj_pcm_default_message = esc_html(APJ_PCM_DEFAULT_MESSAGE, 'Recall-confirm-message');
            $pcm_message_show        = stripslashes_deep(esc_attr(get_option(APJ_PCM_OPT_NAME)));
            global $c_message;

            global $wp_scripts;
            $wp_scripts->add_inline_script( 'jquery-ui-dialog', '
            
            
            
            var publish = document.getElementById("publish");
            
            var $dialog = jQuery("#dialog-recall").dialog({
              autoOpen: false,
              modal: true
            });
            
            
            $dialog.dialog("option", "buttons", [
                {
                  text: "Да",
                  class:"yes",
                  click: function() {
                    jQuery(publish).attr("data-save", true);
                    jQuery(".acf-field-6127c584d3948 input[type=hidden]").val(0);
                    jQuery( this ).dialog( "close" );
                    publish.click();
                  }
                },
                {
                  text: "Нет",
                  class:"no",
                  click: function() {
                    jQuery(publish).attr("data-save", true);
                    jQuery(".acf-field-6127c584d3948 input[type=hidden]").val(1);
                    jQuery( this ).dialog( "close" );
                    publish.click();
                  }
                }
            ]);
            
            if (publish !== null && jQuery(".acf-field-6127c584d3948 input[type=hidden]").val() != 0) {
            
                 window.addEventListener("beforeunload", function(event){
                    
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    event.stopPropagation();
                    
                    
                    if(event.target.activeElement.getAttribute("id") != "publish" && publish.hasAttribute("data-save") == false){
                        $dialog.dialog("open");
                        return false;
                    }
                   
                   
                 });
                 
                 publish.onclick = function(event){ 
                    if(publish.hasAttribute("data-save") == false){
                        event.stopImmediatePropagation();
                        event.stopPropagation();
                        
                        $dialog.dialog("open");
                        return false;
                    }
                    else{
                        return true;
                    }
                    
                 };
            }
            ' );



        }

    }

    /**
     * Admin notices
     * @return void
     */
    public static function adminNotices()
    {
        if (get_option(APJ_PCM_OPT_ERR_NAME) && get_current_screen()->id == 'recall')
        {
            $class   = 'notice notice-error';
            $message = stripslashes_deep(esc_attr(get_option(APJ_PCM_OPT_ERR_NAME)));
            printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
        }
    }
    /**
     * APJConfirmRecall
     * @return string
     */
    public static function APJConfirmRecall()
    {
        if(isset($_SERVER['HTTP_REFERER'])){
            $parsed = parse_url($_SERVER['HTTP_REFERER']);

            if (get_current_screen()->id == 'recall' && get_current_screen()->action != 'add' && $parsed['path'] != '/wp-admin/post-new.php'){
                $apj_pcm_default_message = esc_html(APJ_PCM_DEFAULT_MESSAGE, 'Recall-confirm-message');
                $pcm_message_show        = stripslashes_deep(esc_attr(get_option(APJ_PCM_OPT_NAME)));
                global $c_message;

//            echo '
//            <div id="dialog-recall" title="Подтверждение обработки">
//                '.$pcm_message_show.'
//            </div>
//            ';


                echo '
            <script>
                var publish = document.getElementById("publish");
               
                var originNew = jQuery(".acf-field-6127c584d3948 input[type=hidden]").val();
                console.log(originNew);
                
                var data = {
                    action:"set_acf",
                    post_id: '.get_the_ID().',
                    acf_name: "is_new",
                    acf_value: 0
                };
                jQuery.post( ajaxurl, data).done(function( data ) {
                    console.log(data);
                });
            </script>

            ';
            }
        }
    }
    /**
     * Admin menu
     * @return void
     */
    public static function adminMenu()
    {
        add_options_page('Recall Confirm Message Settings', 'Recall Confirm Message', 'manage_options', plugin_dir_path(__FILE__) . 'admin/adminpage.php');
    }
    /**
     * Admin footer
     * @return void
     */
    public static function adminFooter()
    {?>
        <p> <?php _e('[ Currently this plugin not compatible with <b>Gutenberg Editor.</b> ]', 'apjMC'); ?><br><a href="https://wordpress.org/support/plugin/Recall-confirm-message/reviews/#new-post" class="apj-review-link" target="_blank"><?php echo sprintf(__('If you like <strong> %s </strong> please leave us a &#9733;&#9733;&#9733;&#9733;&#9733; rating.', 'apjMC') , APJ_PCM_PLUGIN_NAME); ?></a>  <?php _e('Thank you.', 'apjMC'); ?></p>
    <?php
    }
    /**
     * Plugin row meta/action links
     * @return void
     */

    public static function PluginActionLinks($links_array, $plugin_file_name)
    {
        if (strpos($plugin_file_name, APJ_PCM_PLUGIN_PATH)) $links_array = array_merge(array(
            '<a href="' . admin_url('admin.php?page='.APJ_PCM_MENU_SLUG.'') . '">Settings</a>'
        ) , $links_array);
        return $links_array;
    }
}


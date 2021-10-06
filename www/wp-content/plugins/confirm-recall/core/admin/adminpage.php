<?php
if (!defined('ABSPATH'))
{
    die('-1');
}
/**
 * @package Recall Confirm Message
 * @version 1.2
 * @since 1.0
 */
if (!current_user_can('manage_options'))
{
    wp_die(__('You do not have sufficient permissions to access this page.'));
}
?>
<div class="wrap">
<h1>Recall Confirm Message Settings</h1>
<?php
$apj_pcm_default_message = APJ_PCM_DEFAULT_MESSAGE;
$apj_pcm_opt_name        = APJ_PCM_OPT_NAME;
if (isset($_POST["submit"]) && $_POST['action'] == 'apj_update_general')
{
    $pcm_message_show    = sanitize_text_field($_POST[$apj_pcm_opt_name]);
    update_option($apj_pcm_opt_name, $pcm_message_show);
    echo '<div id="message" class="updated fade"><p>Message saved.</p></div>';
}
else
{
    $pcm_message_show = get_option($apj_pcm_opt_name);
}
?>
<div>
    <fieldset>
        <form method="post" action=""> 
            <table class="form-table" role="presentation">
                <tbody><tr>
                    <th scope="row"><label for="<?php echo $apj_pcm_opt_name; ?>">Enter New Message :</label></th>
                    <td> <input type="text" id="<?php echo $apj_pcm_opt_name; ?>" name="<?php echo $apj_pcm_opt_name; ?>" value="<?php echo (empty($pcm_message_show) ? esc_attr($apj_pcm_default_message) : esc_attr($pcm_message_show)); ?>" class="regular-text" required/></td>
                </tr>
            </tbody></table>
            <p class="submit"><input type="hidden" name="action" value="apj_update_general" /><input type="submit" value="Save Changes" class="button button-primary" name="submit" /></p>
        </form>
    </fieldset>        
</div>
</div>

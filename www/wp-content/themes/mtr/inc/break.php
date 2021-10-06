<?php
function replaceTagsForManagerBreak($post_id, $template){

    $arPost = get_post($post_id);
    $arAcf = get_fields($post_id);

    $template = str_replace('{post_ID}', $arPost->ID, $template);
    $template = str_replace('{home_url}', home_url(), $template);
    $template = str_replace('{manager_break_modification_datetime}', $arPost->post_modified, $template);
    $template = str_replace('{manager_break_creation_datetime}', $arPost->post_date, $template);

    $template = str_replace('{manager_name}', $arAcf['manager']['display_name'], $template);
    $template = str_replace('{email_manager}', $arAcf['manager']['user_email'], $template);


    return $template;
}

add_action('admin_bar_menu', 'add_item', 9999);
function add_item( $admin_bar ){
    $user = wp_get_current_user();
    $roles = (array)$user->roles;

    if(in_array('manager', $roles)){
        $admin_bar->add_menu( array( 'id'=>'break','title'=>'Уйти на перерыв','href'=>'#' ) );
    }
}
add_action( 'admin_footer', 'break_action_js' );
add_action( 'wp_footer', 'break_action_js', 9999 );
function break_action_js() { ?>
    <?if(is_user_logged_in()){?>
        <style>
            li#wp-admin-bar-break .ab-item{
                background-color: green;
            }

            div.break{
                position: absolute;
                top: 0;
                left: 0;
                background-color: #00800080;
                width: 100%;
                height: 110%;
                z-index: 999999;
                overflow: hidden;
            }
            div.break div{
                display: flex;
                flex-direction: column;
                flex-wrap: nowrap;
                align-content: center;
                justify-content: center;
                align-items: center;
                height: 75vh;
            }
            div.break div p{
                color: white;
                font-size: xxx-large;
                margin: 0;
                margin-bottom: 1rem;
                font-weight: bold;
            }
            div.break div button{
                background: #02d302;
                padding: 1.3rem;
                color: white;
                border: none;
                border-radius: 10px;
                transition: 0.1s all;
                font-weight: bold;
            }
            div.break div button:hover,
            div.break div button:focus {
                cursor: pointer;
                background: #019901;
            }
        </style>

        <div class="break" <?
        $user = wp_get_current_user();
        $roles = (array)$user->roles;

        if(in_array('manager', $roles)){
            $var = get_field('break','user_'. $user->ID);
            if($var == 0){?>
                style="display:none;"
            <?}
        }else if(!in_array('manager', $roles)){?>
            style="display:none;"
        <?}?>>
            <div>
                <p>
                    Перерыв
                </p>
                <button class="from_break">Вернуться к работе</button>
            </div>

        </div>
        <script>
            <?$roles = (array)$user->roles;

            if(in_array('manager', $roles)){
            $var = get_field('break','user_'. $user->ID);
            if($var == 1){?>
            jQuery("#wp-admin-bar-break .ab-item").hide();

            jQuery("body").css({
                "overflow-y": "hidden"
            });
            <?}
            }
            ?>

            jQuery("#wp-admin-bar-break .ab-item").on( "click", function() {
                var data = {
                    'action': 'in_break',
                    'user_id': <?=wp_get_current_user()->ID?>
                };
                jQuery.post(ajaxurl, data, function(response) {
                    jQuery("#wp-admin-bar-break .ab-item").hide();
                    jQuery("div.break").show();
                    jQuery("body").css({
                        "overflow-y": "hidden"
                    });
                });

            });

            jQuery("div.break button.from_break").on( "click", function() {
                var data = {
                    'action': 'from_break',
                    'user_id': <?=wp_get_current_user()->ID?>
                };

                jQuery.post(ajaxurl, data, function(response) {
                    jQuery("div.break").hide();
                    jQuery("#wp-admin-bar-break .ab-item").show();
                    jQuery("body").css({
                        "overflow-y": "auto"
                    });
                });

            });
        </script>
    <?}?>
<?}


add_action( 'wp_ajax_in_break', 'in_break_callback' );
add_action( 'wp_ajax_nopriv_in_break', 'in_break_callback' );
function in_break_callback() {
    $user_id = $_POST['user_id'];
    update_field('break',1,'user_'.$user_id);

    $user = get_user_by('ID',$user_id);
    $post_data = array(
        'post_status'   => 'publish',
        'post_type'     => 'manager_break',
        'post_author'   => 1,
        'post_title'    => 'Менеджер '.$user->display_name.' ('.$user_id.') ушел на перерыв'
    );
    $post_id = wp_insert_post( $post_data );
    update_field('manager', $user_id, $post_id);


    //MAIL
    $mailEvents = new WP_Query( [
        'post_type'     => 'notification',
        'post_status'   => 'draft',
        'posts_per_page'=> -1,

    ]);
    foreach ($mailEvents->posts as $event){
        $event_content = json_decode($event->post_content, true);

        if($event_content['enabled'] == false && $event_content['trigger'] == 'post/manager_break/published'){
            $subject = 'Менеджер '.$user->display_name.' ('.$user_id.') ушел на перерыв';
            $message = replaceTagsForManagerBreak($post_id, $event_content['carriers']['email']['body']);

	        $headers = "MIME-Version: 1.0". "\r\n";;
	        $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	        $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";
            
	        foreach ($event_content['carriers']['email']['recipients'] as $recipient){
		        mail( $recipient['recipient'], $subject, $message, $headers );
	        }
        }
    }




    wp_send_json_success();
}
add_action( 'wp_ajax_from_break', 'from_break_callback' );
add_action( 'wp_ajax_nopriv_from_break', 'from_break_callback' );
function from_break_callback() {
    $user_id = $_POST['user_id'];
    update_field('break',0,'user_'.$user_id);

    $user = get_user_by('ID',$user_id);
    $post_data = array(
        'post_status'   => 'publish',
        'post_type'     => 'manager_break',
        'post_author'   => 1,
        'post_title'    => 'Менеджер '.$user->display_name.' ('.$user_id.') вернулся к работе'
    );
    $post_id = wp_insert_post( $post_data );
    update_field('manager', $user_id, $post_id);


    //MAIL
    $mailEvents = new WP_Query( [
        'post_type'     => 'notification',
        'post_status'   => 'draft',
        'posts_per_page'=> -1,

    ]);
    foreach ($mailEvents->posts as $event){
        $event_content = json_decode($event->post_content, true);

        if($event_content['enabled'] == false && $event_content['trigger'] == 'post/manager_break/published'){
            $subject = 'Менеджер '.$user->display_name.' ('.$user_id.') вернулся к работе';
            $message = replaceTagsForManagerBreak($post_id, $event_content['carriers']['email']['body']);

            if(count($event_content['carriers']['email']['recipients']) == 0){

                wp_mail( $event_content['carriers']['email']['recipients'][0]['recipient'], $subject, $message );
            }
            else{
                foreach ($event_content['carriers']['email']['recipients'] as $recipient){

                    wp_mail( $recipient['recipient'], $subject, $message );

                }
            }
        }
    }






    wp_send_json_success();
}

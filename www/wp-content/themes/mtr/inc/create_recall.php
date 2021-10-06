<?php
function replaceTagsForRecall($post_id, $template){

    $arPost = get_post($post_id);
    $arAcf = get_fields($post_id);

//    $arAcfPrint = print_r($arAcf, true);
//    error_log('<pre>'.$arAcfPrint.'</pre>');
//
//    $arAcfPrint = print_r(get_post_custom($post_id), true);
//    error_log('<pre>'.$arAcfPrint.'</pre>');


    $template = str_replace('{post_ID}', $arPost->ID, $template);
    $template = str_replace('{home_url}', home_url(), $template);
    $template = str_replace('{recall_modification_datetime}', $arPost->post_modified, $template);
    $template = str_replace('{name_client}', $arAcf['name_client'], $template);
    $template = str_replace('{contact}', $arAcf['contact'], $template);
    $template = str_replace('{comment}', $arAcf['comment'], $template);

    return $template;
}

add_action( 'wp_ajax_create_recall', 'create_recall_callback' );
add_action( 'wp_ajax_nopriv_create_recall', 'create_recall_callback' );
function create_recall_callback() {

    if(empty($_POST['name']) && empty($_POST['phone']) && empty($_POST['comment'])) {
        wp_send_json_error('Не указаны контактные данные');
    }

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $comment = $_POST['comment'];

    $post_data = array(
        'post_status'   => 'publish',
        'post_type'     => 'recall',
        'post_author'   => 1,
    );
    $post_id = wp_insert_post( $post_data );

    update_field('name_client', $name, $post_id);
    update_field('contact', $phone, $post_id);
    update_field('comment', $comment, $post_id);
    update_field('manager', get_option('current_manager'), $post_id);
    update_field('is_new', 1, $post_id);


    $mailEvents = new WP_Query( [
        'post_type'     => 'notification',
        'post_status'   => 'draft',
        'posts_per_page'=> -1,

    ]);

    foreach ($mailEvents->posts as $event){
        $event_content = json_decode($event->post_content, true);

        if($event_content['enabled'] == false && $event_content['trigger'] == 'post/recall/published'){
            $subject = $event_content['carriers']['email']['subject'];
            $message = replaceTagsForRecall($post_id, $event_content['carriers']['email']['body']);

	        $headers = "MIME-Version: 1.0". "\r\n";;
	        $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	        $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";

	        foreach ($event_content['carriers']['email']['recipients'] as $recipient){

		        mail( $recipient['recipient'], $subject, $message, $headers );

	        }
        }
    }

    wp_send_json_success('Спасибо за обращение. В ближайшее время с вами свяжутся наши менеджеры');
}

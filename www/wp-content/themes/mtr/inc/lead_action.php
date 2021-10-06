<?php
function transliterate($textcyr = null, $textlat = null) {
    $cyr = array(
        'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
        'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я');
    $lat = array(
        'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
        'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q');
    if($textcyr) return str_replace($cyr, $lat, $textcyr);
    else if($textlat) return str_replace($lat, $cyr, $textlat);
    else return null;
}
function generate_unique_username() {
    $user_name = 'customer_'.random_int(0, PHP_INT_MAX);
    if(username_exists($user_name) == false){
        return $user_name;
    }else{
        generate_unique_username();
    }
}
function replaceTagsForCustomer($user_id, $template){

    $arUser = get_user_by('ID', $user_id);
    $arAcf = get_fields('user_'.$user_id);


    $template = str_replace('{user_ID}', $user_id, $template);
    $template = str_replace('{home_url}', home_url(), $template);

    $template = str_replace('{name}', $arUser->first_name, $template);
    $template = str_replace('{type}', $arAcf['type']['label'], $template);
    $template = str_replace('{type}', $arAcf['telephone'], $template);
    $template = str_replace('{email}', $arUser->user_email, $template);

    return $template;
}
function replaceTagsForOrders($post_id, $template){

    $arPost = get_post($post_id);
    $arAcf = get_fields($post_id);

    $template = str_replace('{post_ID}', $arPost->ID, $template);
    $template = str_replace('{home_url}', home_url(), $template);
    $template = str_replace('{orders_modification_datetime}', $arPost->post_modified, $template);


    $template = str_replace('{status}', $arAcf['status']->post_title, $template);
    $template = str_replace('{from_country}', $arAcf['from_country']->post_title, $template);
    $template = str_replace('{from_city}', $arAcf['from_city'], $template);
    $template = str_replace('{to_city}', $arAcf['to_city'], $template);
    $template = str_replace('{from_storage}', $arAcf['from_storage']->post_title, $template);
    $template = str_replace('{delivery_route}', $arAcf['delivery_route']->post_title, $template);
    $template = str_replace('{estimated_shipping_cost}', $arAcf['estimated_shipping_cost'], $template);
    $template = str_replace('{client_name}', get_user_by('ID', $arAcf['customer_id'])->first_name, $template);
    $template = str_replace('{customer_id}', $arAcf['customer_id'], $template);
    $template = str_replace('{cargo_volume}', $arAcf['cargo_volume'], $template);
    $template = str_replace('{cargo_weight}', $arAcf['cargo_weight'], $template);
    $template = str_replace('{cargo_type}', $arAcf['cargo_type']->post_title, $template);
    $template = str_replace('{name_variable}', $arAcf['paremetrs']['name_variable'], $template);
    $template = str_replace('{count_variable}', $arAcf['paremetrs']['count_variable'], $template);
    $template = str_replace('{need_container}', $arAcf['need_container'] ? 'Да' : 'Нет', $template);


    return $template;
}

add_filter( 'post_row_actions', 'links', 10, 2 );
function links( $actions, $post ) {
    if($post->post_type == 'leads'){
        unset($actions['view']);

        if(!empty(get_field('client_name', $post->ID)) && !empty(get_field('email', $post->ID))){
            if(email_exists(get_field('email', $post->ID)) == false){
                $actions['create_customer'] = '<a href="javascript:void(0)" data-post-id="'.$post->ID.'" class="create_customer_from_lead" onclick="click_create_customer(this)">Создать клиента</a>';
            }
        }
        if(empty(get_field('order_id', $post->ID))){
            $actions['create_order'] = '<a href="javascript:void(0)" data-post-id="'.$post->ID.'" onclick="click_create_order(this)" class="create_order_from_lead">Создать заявку</a>';
        }else{
            $order = get_post(get_field('order_id', $post->ID));
            if(empty($order)){
                $actions['create_order'] = '<a href="javascript:void(0)" data-post-id="'.$post->ID.'" onclick="click_create_order(this)" class="create_order_from_lead">Создать заявку</a>';
            }
        }
    }

    return $actions;
}
add_action('admin_footer', 'script_lead_actions', 9999);
function script_lead_actions()
{
    echo '
    <script>
        function click_create_customer(btn) {   
            var postID = btn.dataset.postId;
            jQuery(btn).remove();
            
            if(postID != "" && postID != null && postID != 0){
                var data = {
                    action:"create_customer_from_lead",
                    post_id: postID,
                };
                jQuery.post( ajaxurl, data).done(function( data ) {
                    if(data.success == true){
                        alert("Клиент из лида создан");
                    }
                    else if (data.success == false){
                        alert("Произошла ошибка при создании клиента. \n\n Error Message: "+data.data);
                    }
                });   
            }
        }       
        
        function click_create_order(btn) {
          var postID = btn.dataset.postId;
            jQuery(btn).remove();
            
            if(postID != "" && postID != null && postID != 0){
                var data = {
                    action:"create_customer_from_lead",
                    post_id: postID,
                };
                jQuery.post( ajaxurl, data).done(function( data ) {
                    if(data.success == true){
                        alert("Клиент из лида создан");
                    }
                    else if (data.success == false){
                        alert("Произошла ошибка при создании клиента. \n\n Error Message: "+data.data);
                    }
                });   
            }
        }
    </script>
    ';
}


add_action( 'wp_ajax_create_customer_from_lead', 'create_customer_from_lead_callback' );
add_action( 'wp_ajax_nopriv_create_customer_from_lead', 'create_customer_from_lead_callback' );
function create_customer_from_lead_callback() {

    if(empty($_POST['post_id'])) {
        wp_send_json_error('Нет post_id');
    }

    $post_id = $_POST['post_id'];
    $lead = get_post($post_id);
    $arAcf = get_fields($post_id);

    $user_name = '';
    if(!empty($arAcf['client_name'])){
        $user_name = transliterate(str_replace(' ', '_', strtolower($arAcf['client_name'])));
    }
    else{
       $user_name = generate_unique_username();
    }
    $user_email = '';
    if(!empty($arAcf['email'])){
        $user_email = $arAcf['email'];
    }
    else{
        $user_email = $user_name.'@test.ru';
    }

    $phone = $arAcf['phone_text'];

    $random_password = wp_generate_password( 12 );
    $user_id = wp_insert_user([
        'user_login' => strtolower($user_name),
        'user_pass'  => $random_password,
        'user_email' => $user_email,
        'first_name' => $arAcf['client_name'] != '' ? $arAcf['client_name'] : 'Клиент',
        'nickname'   => strtolower($user_name),
        'role'       => 'client'
    ]);
    if( ! is_wp_error( $user_id ) ) {
        update_field('customer_id', $user_id, $lead->ID);
        if(!empty($phone)){
            update_field('telephone', $phone, 'user_'.$user_id);
        }
        update_field('type', 'ur', 'user_'.$user_id);
    }


    //MAIL
    $mailEvents = new WP_Query( [
        'post_type'     => 'notification',
        'post_status'   => 'draft',
        'posts_per_page'=> -1,

    ]);
    foreach ($mailEvents->posts as $event){
        $event_content = json_decode($event->post_content, true);

        if($event_content['enabled'] == false && $event_content['trigger'] == 'user/registered'){
            $subject = $event_content['carriers']['email']['subject'];
            $message = replaceTagsForCustomer($user_id, $event_content['carriers']['email']['body']);

	        $headers = "MIME-Version: 1.0". "\r\n";;
	        $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	        $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";

	        foreach ($event_content['carriers']['email']['recipients'] as $recipient){
		        mail( $recipient['recipient'], $subject, $message, $headers);
	        }
        }
    }

    wp_send_json_success();
}

function createCustomer($post_id){

    $lead = get_post($post_id);
    $arAcf = get_fields($post_id);

    $user_name = '';
    if(!empty($arAcf['client_name'])){
        $user_name = transliterate(str_replace(' ', '_', strtolower($arAcf['client_name'])));
    }
    else{
        $user_name = generate_unique_username();
    }
    $user_email = '';
    if(!empty($arAcf['email'])){
        $user_email = $arAcf['email'];
    }
    else{
        $user_email = $user_name.'@test.ru';
    }

    $phone = $arAcf['phone_text'];

    $random_password = wp_generate_password( 12 );
    $user_id = wp_insert_user([
        'user_login' => strtolower($user_name),
        'user_pass'  => $random_password,
        'user_email' => $user_email,
        'first_name' => $arAcf['client_name'] != '' ? $arAcf['client_name'] : 'Клиент',
        'nickname'   => strtolower($user_name),
        'role'       => 'client'
    ]);
    if( ! is_wp_error( $user_id ) ) {
        update_field('customer_id', $user_id, $lead->ID);
        if(!empty($phone)){
            update_field('telephone', $phone, 'user_'.$user_id);
        }
        update_field('type', 'ur', 'user_'.$user_id);
    }


    //MAIL
    $mailEvents = new WP_Query( [
        'post_type'     => 'notification',
        'post_status'   => 'draft',
        'posts_per_page'=> -1,

    ]);
    foreach ($mailEvents->posts as $event){
        $event_content = json_decode($event->post_content, true);

        if($event_content['enabled'] == false && $event_content['trigger'] == 'user/registered'){
            $subject = $event_content['carriers']['email']['subject'];
            $message = replaceTagsForCustomer($user_id, $event_content['carriers']['email']['body']);

	        $headers = "MIME-Version: 1.0". "\r\n";;
	        $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	        $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";

	        foreach ($event_content['carriers']['email']['recipients'] as $recipient){
		        mail( $recipient['recipient'], $subject, $message, $headers );
	        }
        }
    }

}

add_action( 'wp_ajax_create_order_from_lead', 'create_order_from_lead_callback' );
add_action( 'wp_ajax_nopriv_create_order_from_lead', 'create_order_from_lead_callback' );
function create_order_from_lead_callback(){
    if(empty($_POST['post_id'])) {
        wp_send_json_error('Нет post_id');
    }

    $post_id = $_POST['post_id'];
    $lead = get_post($post_id);
    $arAcf = get_fields($post_id);


    if (empty($arAcf['cargo_type']) && !empty($arAcf['cargo_type_client'])) {
        wp_send_json_error(
            'Невозможно создать заказ на базе данного лида, так как
            необходимо добавить тип груза в справочник и привязать его к данному лиду, либо указать уже существующий тип груза,
            который соответствует типу груза указанному клиентом.'
        );
    }

    if (empty($arAcf['customer_id'])) {
        createCustomer($lead->ID);

        // Обновим заявку для получения новых полей
        $arAcf = get_fields($post_id);
    }

    $orderStatus = (new WP_Query([
        'post_type'         => 'statuses_for_order',
        'posts_per_page'    => 1,
        'orderby'           => 'id',
        'order'             => 'ASC',
        'post_status'       => 'publish',

    ]))->post;


    if (empty($orderStatus)) {
        wp_send_json_error('Нет доступных статусов заказа, обратитесь к
            администратору для создания данных статусов, если есть доступ к данному разделу, статусы можно
            созать самостоятельно');
    }


    $post_data = array(
        'post_status'   => 'publish',
        'post_type'     => 'orders',
        'post_author'   => 1,
    );
    $post_id = wp_insert_post( $post_data );
    update_field('cargo_volume', $arAcf['cargo_volume'], $post_id);
    update_field('cargo_weight', $arAcf['cargo_weight'], $post_id);
    update_field('cargo_type', $arAcf['cargo_type']->ID, $post_id);
    update_field('need_container', $arAcf['need_container'], $post_id);
    update_field('from_country', $arAcf['from_country']->ID, $post_id);
    update_field('from_city', $arAcf['from_city'], $post_id);
    update_field('to_city', $arAcf['to_city'], $post_id);
    update_field('from_storage', $arAcf['from_storage']->ID, $post_id);
    update_field('delivery_route', $arAcf['delivery_route']->ID, $post_id);
    update_field('estimated_shipping_cost', $arAcf['estimated_shipping_cost'], $post_id);
    update_field('client', $arAcf['customer_id'], $post_id);
    update_field('manager', $arAcf['manager']['ID'], $post_id);
    update_field('name_variable', $arAcf['parametrs']['name_variable'], $post_id);
    update_field('count_variable', $arAcf['parametrs']['count_variable'], $post_id);
    update_field('lead_id', $lead->ID, $post_id);


    //MAIL
    $mailEvents = new WP_Query( [
        'post_type'     => 'notification',
        'post_status'   => 'draft',
        'posts_per_page'=> -1,

    ]);
    foreach ($mailEvents->posts as $event){
        $event_content = json_decode($event->post_content, true);

        if($event_content['enabled'] == false && $event_content['trigger'] == 'post/orders/published'){
            $subject = $event_content['carriers']['email']['subject'];
            $message = replaceTagsForOrders($post_id, $event_content['carriers']['email']['body']);
	        $headers = "MIME-Version: 1.0". "\r\n";;
	        $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	        $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";

	        foreach ($event_content['carriers']['email']['recipients'] as $recipient){
		        mail( $recipient['recipient'], $subject, $message, $headers );
	        }
        }
    }


    wp_send_json_success('Заявка на базе лида успешно создана.');
}

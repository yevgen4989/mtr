<?php
function replaceTagsForLeads($post_id, $data, $template){

    $arPost = get_post($post_id);
    $arAcf = get_fields($post_id);

    $template = str_replace('{post_ID}', $arPost->ID, $template);
    $template = str_replace('{home_url}', home_url(), $template);
    $template = str_replace('{leads_modification_datetime}', $arPost->post_modified, $template);
    $template = str_replace('{leads_creation_datetime}', $arPost->post_date, $template);

	$template = str_replace('{status}', $data['status']->post_title, $template);

	if(isset($arAcf['from_country']) && !empty($arAcf['from_country'])){
		$template = str_replace('{from_country}', $arAcf['from_country']->post_title, $template);
	}
	else{
		$template = str_replace('{from_country}', get_post($data['country_id'])->post_title, $template);
	}

	if(isset($arAcf['from_storage']) && !empty($arAcf['from_storage'])){
		$template = str_replace('{from_storage}', $arAcf['from_storage']->post_title, $template);
	}
	else{
		$template = str_replace('{from_storage}', get_post($data['storeFrom'])->post_title, $template);
	}

	if(isset($arAcf['delivery_route']) && !empty($arAcf['delivery_route'])){
		$template = str_replace('{delivery_route}', $arAcf['delivery_route']->post_title, $template);
	}
	else{
		$template = str_replace('{delivery_route}', $data['delivery_route']->post_title, $template);
	}

	if(isset($arAcf['cargo_type']) && !empty($arAcf['cargo_type'])){
		$template = str_replace('{cargo_type}', $arAcf['cargo_type']->post_title, $template);
	}
	else{
		if(!empty($data['cargo_type'])){
			$template = str_replace('{cargo_type}', $data['cargo_type']->post_title, $template);
		}
		else{
			$template = str_replace('{cargo_type}', $data['user_type_cargo'], $template);
		}
	}

	if(!empty($arAcf['estimated_shipping_cost'])){
		$template = str_replace('{estimated_shipping_cost}', $arAcf['estimated_shipping_cost'], $template);
	}
	else{
		$template = str_replace('{estimated_shipping_cost}', $data['price_per_kilo'], $template);
	}
	if(!empty($arAcf['from_city'])){
		$template = str_replace('{from_city}', $arAcf['from_city'], $template);
	}
	else{
		$template = str_replace('{from_city}', $data['city_from'], $template);
	}
	if(!empty($arAcf['to_city'])){
		$template = str_replace('{to_city}', $arAcf['to_city'], $template);
	}
	else{
		$template = str_replace('{to_city}', $data['city_to'], $template);
	}
	if(!empty($arAcf['client_name'])){
		$template = str_replace('{client_name}', $arAcf['client_name'], $template);
	}
	else{
		$template = str_replace('{client_name}', $data['name_customer'], $template);
	}
	if(!empty($arAcf['phone_text'])){
		$template = str_replace('{phone}', $arAcf['phone_text'], $template);
	}
	else{
		$template = str_replace('{phone}', $data['phone'], $template);
	}
	if(!empty($arAcf['email'])){
		$template = str_replace('{email}', $arAcf['email'], $template);
	}
	else{
		$template = str_replace('{email}', $data['email'], $template);
	}
	if(!empty($arAcf['cargo_volume'])){
		$template = str_replace('{cargo_volume}', $arAcf['cargo_volume'], $template);
	}
	else{
		$template = str_replace('{cargo_volume}', $data['volume'], $template);
	}
	if(!empty($arAcf['cargo_volume'])){
		$template = str_replace('{cargo_weight}', $arAcf['cargo_weight'], $template);
	}
	else{
		$template = str_replace('{cargo_weight}', $data['weight'], $template);
	}
	if (!empty($arAcf['cargo_type_client'])){
		$template = str_replace('{cargo_type_client}', $arAcf['cargo_type_client'], $template);
	}
	else{
		$template = str_replace('{cargo_type_client}', $data['user_type_cargo'], $template);
	}

	if(!empty($arAcf['parametrs'])){
		$template = str_replace('{name_variable}', $arAcf['parametrs']['name_variable'], $template);
		$template = str_replace('{count_variable}', $arAcf['parametrs']['count_variable'], $template);
	}
	else{
		$template = str_replace('{name_variable}', 'Нет данных', $template);
		$template = str_replace('{count_variable}', 'Нет данных', $template);
	}

    $template = str_replace('{need_container}', $arAcf['need_container'] ? 'Да' : 'Нет', $template);
    $template = str_replace('{referer}', $arAcf['referer'], $template);


    return $template;
}

add_action( 'wp_ajax_lead_create', 'lead_create_callback' );
add_action( 'wp_ajax_nopriv_lead_create', 'lead_create_callback' );
function lead_create_callback() {

    $cargo = get_post($_POST['type_cargo_id']);
    $userTypeCargo = $_POST['user_type_cargo'];

    if (empty($cargo) && empty($userTypeCargo)) {
        wp_send_json_error('Неверно указан тип груза.');
    }

    $country = get_post($_POST['country_id']);
    if (empty($country)) {
        wp_send_json_error('К сожалению из указанной страны доставка груза не осуществляется.');
    }
    $mainCountry = get_post(get_option('main_country'));

    $storeFrom = get_field('store', $country->ID);
    if (empty($storeFrom)) {
        wp_send_json_error('В данной стране нет обслуживающего склада, доставка, к сожанеию, невозможна.');
    }

    $storeTo = get_field('store', $mainCountry->ID);
    if (empty($storeTo)) {
        wp_send_json_error('Не найден склад для хранения груза в стране назначения. Доставка, к сожалению, невозможна.');
    }

    $deliveryMethod = new WP_Query([
        'post_type' => 'delivery_methods',
        'posts_per_page' => 1,
        'orderby' => 'rand'
    ]);
    $deliveryMethod = $deliveryMethod->post;
    if (empty($deliveryMethod)) {
        wp_send_json_error('Нет доступных способов доставки из данной страны.');
    }


    $deliveryRoute = new WP_Query( [
        'posts_per_page' => -1,
        'post_type' => 'delivery_route',
        'post_status' => 'publish',
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'from_store',
                'value' => $storeFrom->ID
            ],
            [
                'key' => 'to_store',
                'value' => $storeTo->ID
            ],
            [
                'key' => 'delivery_method',
                'value' => $deliveryMethod->ID
            ]
        ]
    ] );
    $deliveryRoute = $deliveryRoute->post;
    if (empty($deliveryRoute)) {
        wp_send_json_error('Нет доступных маршрутов для вывоза груза из указанной страны. Доставка к сожалению невозможна.');
    }

    $data = array(
        'volume'                => $_POST['volume'] ?? '',
        'weight'                => $_POST['weight'] ?? '',
        'type_cargo_id'         => $_POST['type_cargo_id'] ?? '',
        'user_type_cargo'       => $_POST['user_type_cargo'] ?? '',
        'need_container'        => $_POST['need_container'] ?? '',
        'country_id'            => $_POST['country_id'] ?? '',
        'storeFrom'             => $storeFrom->ID ?? '',
        'delivery_routes'       => $deliveryRoute->ID ?? '',
        'price_per_kilo'        => $_POST['price_per_kilo'] ?? '',
        'name_customer'         => $_POST['name_customer'] ?? '',
        'phone'                 => $_POST['phone'] ?? '',
        'email'                 => $_POST['email'] ?? '',
        'city_from'             => $_POST['city_from'] ?? '',
        'city_to'               => $_POST['city_to'] ?? '',
        'referer'               => $_POST['referer'] ?? '',
        'variable_attributes'   => $_POST['variable_attributes'] ?? '',
    );

    $leadStatus = new WP_Query([
        'post_type' => 'statuses_for_leads',
        'posts_per_page' => 1,
        'orderby' => 'id',
        'order'=>'ASC',
        'meta_query' => [
            [
                'key' => 'status',
                'value' => 1
            ],
        ]

    ]);
    $leadStatus = $leadStatus->post;

	$data['status'] = $leadStatus;
	$data['delivery_route'] = $deliveryRoute;
	$data['delivery_method'] = $deliveryMethod;
	$data['cargo_type'] = $cargo;



    $post_data = array(
        'post_status'   => 'publish',
        'post_type'     => 'leads',
        'post_author'   => 1,
    );
    $post_id = wp_insert_post( $post_data );

    update_field('status', $leadStatus->ID, $post_id);
    update_field('from_country', $data['country_id'], $post_id);
    update_field('from_city', $data['city_from'], $post_id);
    update_field('to_city', $data['city_to'], $post_id);
    update_field('from_storage', $data['storeFrom'], $post_id);
    update_field('delivery_route', $data['delivery_routes'], $post_id);
    update_field('estimated_shipping_cost', $data['price_per_kilo'], $post_id);
    update_field('client_name', $data['name_customer'], $post_id);

    update_field('phone_text', $_POST['phone'], $post_id);
    update_field('email', $_POST['email'], $post_id);
    update_field('cargo_volume', $data['volume'], $post_id);
    update_field('cargo_weight', $data['weight'], $post_id);
    update_field('cargo_type', $data['type_cargo_id'], $post_id);
	if(!empty($data['variable_attributes'])){
		update_field('name_variable', $data['variable_attributes'][0]['name'], $post_id);
		update_field('count_variable', $data['variable_attributes'][0]['value'], $post_id);
	}
    update_field('cargo_type_client', $data['user_type_cargo'], $post_id);
    update_field('need_container', $data['need_container'], $post_id);
    update_field('manager', get_option('current_manager'), $post_id);
    if (empty($_POST['referer'])) {
        $_POST['referer'] = 'Прямой переход';
    }
    update_field('referer', $_POST['referer'], $post_id);


    //MAIL
    $mailEvents = new WP_Query( [
        'post_type'     => 'notification',
        'post_status'   => 'draft',
        'posts_per_page'=> -1,

    ]);
    foreach ($mailEvents->posts as $event){
        $event_content = json_decode($event->post_content, true);

        if($event_content['enabled'] == false && $event_content['trigger'] == 'post/leads/published'){
            $subject = $event_content['carriers']['email']['subject'];
            $message = replaceTagsForLeads($post_id, $data, $event_content['carriers']['email']['body']);

	        $headers = "MIME-Version: 1.0". "\r\n";;
	        $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	        $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";


	        foreach ($event_content['carriers']['email']['recipients'] as $recipient){
		        mail( $recipient['recipient'], $subject, $message, $headers );

	        }
        }
    }

    wp_send_json_success('Ваша заявка успешно сохранена. В ближайшее время с Вами свяжутся наши менеджеры для уточнения деталей заявки.');

}

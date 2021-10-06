<?php

add_action( 'wp_ajax_calculate_create', 'calculate_create_callback' );
add_action( 'wp_ajax_nopriv_calculate_create', 'calculate_create_callback' );
function calculate_create_callback() {

    if(empty($_POST['name']) && empty($_POST['contact']) && empty($_POST['comment'])) {
        wp_send_json_error('Не указаны контактные данные');
    }

    $name = '';
    $phone = $_POST['contact'];
    $comment = 'Груз: ' . $_POST['typeCargo'] . ' Доставляем из: ' . $_POST['country'] . ' Весом: ' . $_POST['weight'] . 'кг';;
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

    //MAIL
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

		        mail( $recipient['recipient'], $subject, $message, $headers);

	        }
        }
    }

    $args = array(
        'post_type'   => 'countries',
        'title'       => $_POST['country'],
        'post_status' => 'publish',
        'post_parent' => 0,
    );
    $country_request = new WP_Query($args);
    $country_request = $country_request->post;

    if (empty($country_request)) {
        $averagePrice = 2.5;
        $price = 0;
        if ($_POST['weight'] < 10) {
            $price = $averagePrice * 10;
        } else {
            $price = $_POST['weight'] * $averagePrice;
        }
        wp_send_json_success(array('price'=>$price));
    }


    $typeCargo = array();
    $answer = array();
    $price = 0;


    $arCargos = array();
    $arCargoBySuggestions = array();

    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'type_cargos',
        'orderby' => 'title',
        's' => $_POST['typeCargo'],
        'order'   => 'ASC',
        'post_status' => 'publish'
    );
    $cargos = new WP_Query( $args );
    if($cargos->post_count > 0){
        foreach ($cargos->posts as $cargo){
            $arCargos[] = (array)$cargo;
        }
        foreach ($arCargos as $cargo) {
            $cargoAcf = get_fields($cargo['ID']);

            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'type_cargo_country',
                'post_status' => 'publish',
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'country',
                        'value' => $country_request->ID
                    ],
                    [
                        'key' => 'type',
                        'value' => $cargo['ID']
                    ],
                ]
            );

            $country = new WP_Query( $args );
            $country = $country->posts[0];
            $countryAcf = get_fields($country->ID);

            if (empty($country)) {
                continue;
            }
            $prices = array();
            if(empty($countryAcf['prices'])){
                $prices = null;
            }else{
                $prices['items'][] = $countryAcf['prices'];
            }


            $variable_params = array();
            if(!empty($cargoAcf['name_unit']) && !empty($cargoAcf['unit']) && !empty($cargoAcf['min']) && !empty($cargoAcf['max'])){
                $variable_params['items'][] = array(
                    'name_param' => $cargoAcf['name_unit'],
                    'unit'       => $cargoAcf['unit'],
                    'min_value'  => $cargoAcf['min'],
                    'max_value'  => $cargoAcf['max']
                );
            }else{
                $variable_params = null;
            }


            $answer = [
                'value' => $cargo['post_title'],
                'data' => [
                    'cargo_id' => $cargo['ID'],
                    'price_per_kilo' => $countryAcf['price_per_kilo'],
                    'need_cube_meter' => $countryAcf['need_cube_meter'],
                    'price_per_cube_meter' => $countryAcf['price_per_cube_meter'],
                    'prices' => $prices,
                    'need_volume_and_weight' => $countryAcf['need_volume_and_weight'],
                    'variable_params' => $variable_params
                ]
            ];
        }
    }
    else{
        //aliases
        $args = array(
            'taxonomy'      => 'aliases',
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => true,
            'search'        => $_POST['typeCargo'],
            'update_term_meta_cache' => true,
        );
        $cargoBySuggestions = new WP_Term_Query( $args );
        if(count($cargoBySuggestions->terms) > 0){
            foreach ($cargoBySuggestions->terms as $term){
                $arCargoBySuggestions[] = (array)$term;
            }
            foreach ($arCargoBySuggestions as $term) {
                $arCargosParent = array();
                $posts_array = get_posts(
                    array(
                        'posts_per_page' => -1,
                        'post_type' => 'type_cargos',
                        'orderby' => 'title',
                        'order'   => 'ASC',
                        'post_status' => 'publish',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'aliases',
                                'field' => 'term_id',
                                'terms' => $term['term_id'],
                            )
                        )
                    )
                );


                foreach ($posts_array as $post){
                    $arPost = (array)$post;
                    $arPost['acf'] = get_fields($arPost['ID']);
                    $arCargosParent[] = $arPost;
                }

                if (empty($arCargosParent)) {
                    continue;
                }

                foreach ($arCargosParent as $cargosItem){

                    $args = array(
                        'posts_per_page' => -1,
                        'post_type' => 'type_cargo_country',
                        'post_status' => 'publish',
                        'meta_query' => [
                            'relation' => 'AND',
                            [
                                'key' => 'country',
                                'value' => $country_request->ID
                            ],
                            [
                                'key' => 'type',
                                'value' => $cargosItem['ID']
                            ],
                        ]
                    );
                    $country = new WP_Query( $args );
                    $country = (array)$country->posts[0];
                    $country['acf'] = get_fields($country['ID']);

                    if (empty($country)) {
                        continue;
                    }

                    $prices = array();
                    if(empty($country['acf']['prices'])){
                        $prices = null;
                    }
                    else{
                        $prices['items'] = $country['acf']['prices'];
                    }

                    $variable_params = array();
                    if(!empty($cargosItem['acf']['name_unit']) && !empty($cargosItem['acf']['unit']) && !empty($cargosItem['acf']['min']) && !empty($cargosItem['acf']['max'])){
                        $variable_params['items'][] = array(
                            'name_param' => $cargosItem['acf']['name_unit'],
                            'unit'       => $cargosItem['acf']['unit'],
                            'min_value'  => $cargosItem['acf']['min'],
                            'max_value'  => $cargosItem['acf']['max']
                        );
                    }
                    else{
                        $variable_params = null;
                    }

                    $answer = [
                        'value' => $term['name'],
                        'data' => [
                            'cargo_id' => $cargosItem['ID'],
                            'price_per_kilo' => $country['acf']['price_per_kilo'],
                            'need_cube_meter' => $country['acf']['need_cube_meter'],
                            'price_per_cube_meter' => $country['acf']['price_per_cube_meter'],
                            'prices' => $prices,
                            'need_volume_and_weight' => $country['acf']['need_volume_and_weight'],
                            'variable_params' => $variable_params
                        ]
                    ];
                }

            }
        }

        if(empty($answer)){
            $arCargos = array();
            $args = array(
                'posts_per_page' => 1,
                'post_type' => 'type_cargos',
                'orderby' => 'title',
                'order'   => 'ASC',
                'post_status' => 'publish'
            );
            $cargos = new WP_Query( $args );
            if($cargos->post_count > 0){
                foreach ($cargos->posts as $cargo){
                    $arCargos[] = (array)$cargo;
                }
                foreach ($arCargos as $cargo) {
                    $cargoAcf = get_fields($cargo['ID']);


                    $args = array(
                        'posts_per_page' => -1,
                        'post_type' => 'type_cargo_country',
                        'post_status' => 'publish',
                        'meta_query' => [
                            'relation' => 'AND',
                            [
                                'key' => 'country',
                                'value' => $country_request->ID
                            ],
                            [
                                'key' => 'type',
                                'value' => $cargo['ID']
                            ],
                        ]
                    );

                    $country = new WP_Query( $args );
                    $country = $country->posts[0];
                    $countryAcf = get_fields($country->ID);

                    if (empty($country)) {
                        continue;
                    }
                    $prices = array();
                    if(empty($countryAcf['prices'])){
                        $prices = null;
                    }else{
                        $prices['items'][] = $countryAcf['prices'];
                    }


                    $variable_params = array();
                    if(!empty($cargoAcf['name_unit']) && !empty($cargoAcf['unit']) && !empty($cargoAcf['min']) && !empty($cargoAcf['max'])){
                        $variable_params['items'][] = array(
                            'name_param' => $cargoAcf['name_unit'],
                            'unit'       => $cargoAcf['unit'],
                            'min_value'  => $cargoAcf['min'],
                            'max_value'  => $cargoAcf['max']
                        );
                    }else{
                        $variable_params = null;
                    }


                    $answer = [
                        'value' => $cargo['post_title'],
                        'data' => [
                            'cargo_id' => $cargo['ID'],
                            'price_per_kilo' => $countryAcf['price_per_kilo'] ?? 0,
                            'need_cube_meter' => $countryAcf['need_cube_meter'] ?? 0,
                            'price_per_cube_meter' => $countryAcf['price_per_cube_meter'] ?? 0,
                            'prices' => $prices ?? 0,
                            'need_volume_and_weight' => $countryAcf['need_volume_and_weight'] ?? 0,
                            'variable_params' => $variable_params
                        ]
                    ];
                }
            }

        }
    }

    if($_POST['weight'] < 10) {
        $_POST['weight'] = 10;
    }
    $price = $answer['data']['price_per_kilo'] * $_POST['weight'];


    wp_send_json_success(array('price'=>ceil(round($price, 0))));

}

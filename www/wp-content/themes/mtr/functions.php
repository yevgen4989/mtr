<?php
include('inc/include.php');

add_filter( 'excerpt_length', function(){
	return 20;
} );


add_filter( 'script_loader_src', 'remove_script_version' );
add_filter( 'style_loader_src', 'remove_script_version' );
function remove_script_version( $src ){
    $parts = explode( '?', $src );
    return $parts[0];
}

//add_filter( 'post_row_actions', 'remove_row_actions',10, 1 );
function remove_row_actions( $actions )
{
	unset( $actions['inline hide-if-no-js'] );
	return $actions;
}

add_action('wp_head', 'ajaxurl');
function ajaxurl() {

    echo '<script>
           let wp = {
               url : "' . admin_url('admin-ajax.php') . '",
               nonce_fast_calculate : "'.wp_create_nonce('nonce_fast_calculate').'"
           };
           let ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

add_action( 'after_setup_theme', 'mtr_setup' );
function mtr_setup() {

	add_action('acf/init', function (){
		remove_filter('acf_the_content', 'wpautop' );
	});

	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption',
		'script',
		'style',
	) );

	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );

	add_theme_support( 'custom-logo', [
		'unlink-homepage-logo' => true,
	] );


	acf_add_options_page(array(
		'page_title' => 'Контактная информация',
		'menu_slug' => 'info',
		'menu_title' => 'Контактная информация',
		'capability' => 'edit_posts',
		'position' => '',
		'parent_slug' => '',
		'icon_url' => '',
		'redirect' => true,
		'post_id' => 'info',
		'autoload' => true,
		'update_button' => 'Обновить',
		'updated_message' => 'Сохранено',
	));

}

function cw_post_type() {
    register_post_type( 'countries',
        array(
            'labels' => array(
                'name' => __( 'Страны' ),
                'singular_name' => __( 'Страны' )
            ),
            'has_archive' => false,
            'hierarchical' => true,
            'public' => true,
            'rewrite' => array('slug' => 'countries'),
            'show_in_rest' => true,
            'supports' => array('editor', 'revisions', 'title', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'page-attributes', 'post-formats'),
            'capability_type' => 'countries',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'type_cargos',
        array(
            'labels' => array(
                'name' => __( 'Типы груза' ),
                'singular_name' => __( 'Типы груза' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'type_cargos'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'title', 'page-attributes'),
            'capability_type' => 'type_cargos',
            'map_meta_cap' => true

        )
    );
    register_taxonomy( 'aliases', [ 'type_cargos' ], [
        'label'                 => '',
        'labels'                => [
            'name'              => 'Псевдонимы',
            'singular_name'     => 'Псевдоним',
            'search_items'      => 'Поиск псевдонимов',
            'all_items'         => 'Все псевдонимы',
            'view_item '        => 'Просмотр псевдонима',
            'parent_item'       => 'Родительский псевдоним',
            'parent_item_colon' => 'Родительский псевдоним:',
            'edit_item'         => 'Редактировать псевдоним',
            'update_item'       => 'Обновить псевдоним',
            'add_new_item'      => 'Добавить новый псевдоним',
            'new_item_name'     => 'Создать псевдоним',
            'menu_name'         => 'Псевдонимы',
        ],
        'public'                => true,
        'hierarchical'          => false,
        'rewrite'               => true,
        'capabilities'          => array(),
        'show_admin_column'     => true,
    ] );
    register_post_type( 'type_cargo_country',
        array(
            'labels' => array(
                'name' => __( 'Стоимость доставки по странам и типам' ),
                'singular_name' => __( 'Стоимость доставки по странам и типам' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'type_cargo_country'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'title', 'page-attributes'),
            'capability_type' => 'type_cargo_countries',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'track_descriptors',
        array(
            'labels' => array(
                'name' => __( 'Дескрипторы статусов трекинга' ),
                'singular_name' => __( 'Дескрипторы статусов трекинга' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'track_descriptors'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'title', 'page-attributes'),
            'capability_type' => 'track_descriptors',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'delivery_route',
        array(
            'labels' => array(
                'name' => __( 'Маршруты доставки' ),
                'singular_name' => __( 'Маршруты доставки' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'delivery_route'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'title', 'page-attributes'),
            'capability_type' => 'delivery_route',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'delivery_methods',
        array(
            'labels' => array(
                'name' => __( 'Способы доставки' ),
                'singular_name' => __( 'Способы доставки' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'delivery_methods'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'title', 'page-attributes'),
            'capability_type' => 'delivery_methods',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'stores',
        array(
            'labels' => array(
                'name' => __( 'Склады' ),
                'singular_name' => __( 'Склады' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'stores'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'title', 'page-attributes'),
            'capability_type' => 'stores',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'track_statuses',
        array(
            'labels' => array(
                'name' => __( 'Статусы заявок' ),
                'singular_name' => __( 'Статусы заявок' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'track_statuses'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'title', 'page-attributes'),
            'capability_type' => 'track_statuses',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'recall',
        array(
            'labels' => array(
                'name' => __( 'Обратный звонок' ),
                'singular_name' => __( 'Обратный звонок' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'recall'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'page-attributes', 'custom-fields'),
            'capability_type' => 'recall',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'manager_actions',
        array(
            'labels' => array(
                'name' => __( 'Замена менеджера' ),
                'singular_name' => __( 'Замена менеджера' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'manager_actions'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'page-attributes'),
            'capability_type' => 'manager_actions',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'statuses_for_leads',
        array(
            'labels' => array(
                'name' => __( 'Статусы для лидов' ),
                'singular_name' => __( 'Статусы для лидов' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'statuses_for_leads'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'title', 'page-attributes'),
            'capability_type' => 'statuses_for_leads',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'statuses_for_orders',
        array(
            'labels' => array(
                'name' => __( 'Статусы для заявок' ),
                'singular_name' => __( 'Статусы для заявок' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'statuses_for_orders'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'title', 'page-attributes'),
            'capability_type' => 'statuses_for_orders',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'leads',
        array(
            'labels' => array(
                'name' => __( 'Лиды' ),
                'singular_name' => __( 'Лиды' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'leads'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'page-attributes', 'comments'),
            'capability_type' => 'leads',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'orders',
        array(
            'labels' => array(
                'name' => __( 'Заявки' ),
                'singular_name' => __( 'Заявки' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'orders'),
            'show_in_rest' => false,
            'supports' => array('revisions', 'page-attributes', 'comments'),
            'capability_type' => 'orders',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'leads_in_process',
        array(
            'labels' => array(
                'name' => __( 'ЛИДЫ В ПРОЦЕССЕ' ),
                'singular_name' => __( 'ЛИДЫ В ПРОЦЕССЕ' ),
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'leads_in_process'),
            'show_in_rest' => false,
            'supports' => array('revisions'),
            'capability_type' => 'leads_in_process',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'manager_break',
        array(
            'labels' => array(
                'name' => __( 'Перерывы менеджеров' ),
                'singular_name' => __( 'Перерывы менеджеров' )
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'manager_break'),
            'show_in_rest' => false,
            'supports' => array('title'),
            'capability_type' => 'manager_break',
            'map_meta_cap' => true
        )
    );
    register_post_type( 'substitution',
        array(
            'labels' => array(
                'name' => __( 'Substitution' ),
                'singular_name' => __( 'Substitution' ),
            ),
            'has_archive' => false,
            'public' => true,
            'rewrite' => array('slug' => 'substitution'),
            'show_in_rest' => false,
            'supports' => array('revisions'),
            'capability_type' => 'substitution',
            'map_meta_cap' => true
        )
    );
}
add_action( 'init', 'cw_post_type' );


add_filter( 'post_class', 'add_class_for_recall_for_new', 9999, 3 );
function add_class_for_recall_for_new( $classes, $class, $post_id ){

    $post_type = get_post_type( $post_id );
    if($post_type == 'recall' && ( get_field('is_new', $post_id) != 0 || get_field('is_new', $post_id) == null)){
        $classes[] = 'post-new';
    }

    return $classes;

}

add_action('admin_head', 'css_for_recall_table');
function css_for_recall_table() {
echo '
    <style>
        table.wp-list-table.table-view-list.posts  tr.post-new {
            background-color: green!important;
        }
        
        table.wp-list-table.table-view-list.posts  tr.post-new td, 
        table.wp-list-table.table-view-list.posts tr.post-new td a,
        table.wp-list-table.table-view-list.posts tr.post-new td button
        {
            color: white!important;
        }
    </style>
';
}

//add_filter('acf/fields/user/result', 'acf_fields_new_manager_object_result', 10, 4);
function acf_fields_new_manager_object_result( $text, $post, $field, $post_id ) {
    $current_manager = get_option('current_manager');

    if($field['key'] == 'field_61293d961e932'){
        if( $post->ID == $current_manager ){
            return $text;
        }
    }
    elseif($field['key'] == 'field_61296d8f42d80' ){
        if($post->ID != $current_manager){
            return $text;
        }
    }else{
        return $text;
    }
	return $text;
}


add_filter('acf/fields/post_object/query/name=status', function ($args, $field, $post_id ) {

    $current_item = get_field('status',$post_id);
    $statuses = get_fields($current_item->ID);
    $arStatuses[] = $current_item->ID;


    if(!empty($statuses['s_for_reverse'])){
        foreach ($statuses['s_for_reverse'] as $item){
            $arStatuses[] = $item->ID;
        }
    }
    if(!empty($statuses['s_for_next'])){
        foreach ($statuses['s_for_next'] as $item){
            $arStatuses[] = $item->ID;
        }
    }

    $args['orderby'] = 'id';
    $args['order'] = 'ASC';
    $args['post__in'] = $arStatuses;
    $args['meta_query'] = [
        [
            'key' => 'status',
            'value' => 1
        ]
    ];


    return $args;
}, 11, 3);

add_action( 'save_post_manager_actions', 'manager_actions_updated', 10, 1 );
function manager_actions_updated( $post_id ) {
	$new_manager = get_post_meta($post_id, 'new_manager');
	update_option('current_manager', $new_manager[0]);
}

function replaceTagsForLeadsStatusChange($post_id, $old_status_id, $new_status_id, $template){

    $arPost = get_post($post_id);
    $arAcf = get_fields($post_id);

    $template = str_replace('{post_ID}', $arPost->ID, $template);
    $template = str_replace('{home_url}', home_url(), $template);
    $template = str_replace('{leads_modification_datetime}', $arPost->post_modified, $template);


    $template = str_replace('{status}', $arAcf['status'] != '' ? $arAcf['status']->post_title : get_post(get_post_meta($post_id, 'status'))->post_title, $template);
    $template = str_replace('{old_status}', get_post($old_status_id)->post_title, $template);
    $template = str_replace('{new_status}', get_post($new_status_id)->post_title, $template);

    $template = str_replace('{from_storage}',                   $arAcf['from_storage'] != '' ? $arAcf['from_storage']->post_title : get_post(get_post_meta($post_id, 'from_storage'))->post_title, $template);
    $template = str_replace('{delivery_route}',                 $arAcf['delivery_route'] != '' ? $arAcf['delivery_route']->post_title : get_post(get_post_meta($post_id, 'delivery_route'))->post_title, $template);
    $template = str_replace('{cargo_type}',                     $arAcf['cargo_type'] != '' ? $arAcf['cargo_type']->post_title : get_post(get_post_meta($post_id, 'cargo_type'))->post_title, $template);
    $template = str_replace('{from_country}',                   $arAcf['from_country'] != '' ? $arAcf['from_country']->post_title : get_post(get_post_meta($post_id, 'from_country'))->post_title, $template);


    $template = str_replace('{from_city}',                      $arAcf['from_city'] != '' ? $arAcf['from_city'] : get_post_meta($post_id, 'from_city'), $template);
    $template = str_replace('{to_city}',                        $arAcf['to_city'] != '' ? $arAcf['to_city'] : get_post_meta($post_id, 'to_city'), $template);
    $template = str_replace('{estimated_shipping_cost}',        $arAcf['estimated_shipping_cost'] != '' ? $arAcf['estimated_shipping_cost'] : get_post_meta($post_id, 'estimated_shipping_cost'), $template);
    $template = str_replace('{client_name}',                    $arAcf['client_name'] != '' ? $arAcf['client_name'] : get_post_meta($post_id, 'client_name'), $template);
    $template = str_replace('{phone}',                          $arAcf['phone_text'] != '' ? $arAcf['phone_text'] : get_post_meta($post_id, 'phone_text'), $template);
    $template = str_replace('{email}',                          $arAcf['email'] != '' ? $arAcf['email'] : get_post_meta($post_id, 'email'), $template);
    $template = str_replace('{cargo_volume}',                   $arAcf['cargo_volume'] != '' ? $arAcf['cargo_volume'] : get_post_meta($post_id, 'cargo_volume'), $template);
    $template = str_replace('{cargo_weight}',                   $arAcf['cargo_weight'] != '' ? $arAcf['cargo_weight'] : get_post_meta($post_id, 'cargo_weight'), $template);
    $template = str_replace('{cargo_type_client}',              $arAcf['cargo_type_client'] != '' ? $arAcf['cargo_type_client'] : get_post_meta($post_id, 'cargo_type_client'), $template);
    $template = str_replace('{name_variable}',                  $arAcf['parametrs']['name_variable'] != '' ? $arAcf['parametrs']['name_variable'] : get_post_meta($post_id, 'parametrs_name_variable'), $template);
    $template = str_replace('{count_variable}',                 $arAcf['parametrs']['count_variable'] != '' ? $arAcf['parametrs']['count_variable'] : get_post_meta($post_id, 'parametrs_count_variable'), $template);
    $template = str_replace('{need_container}',                 $arAcf['need_container'] != '' ? $arAcf['need_container'] : get_post_meta($post_id, 'need_container'), $template);
    $template = str_replace('{referer}',                        $arAcf['referer'] != '' ? $arAcf['referer'] : get_post_meta($post_id, 'referer'), $template);


    return $template;
}
add_filter('acf/update_value/name=status', 'my_acf_update_value', 30, 4);
function my_acf_update_value( $value, $post_id, $field, $original ) {
    if(get_post_type($post_id) == 'leads'){
        $old_value = get_post_meta($post_id, $field['name'], true);
        if ($value != $old_value && $old_value != '') {
            $post_new = wp_insert_post([
                'post_status'   => 'publish',
                'post_type'     => 'leads_in_process',
                'post_author'   => 1,
            ]);
            $manager_lead = get_field('manager', $post_id);

            update_field('lead', $post_id, $post_new);
            update_field('status', $value, $post_new);
            update_field('start_executable', (new DateTime())->format('Y-m-d H:i:s'), $post_new);
            update_field('is_success_executable', 0, $post_new);
            update_field('executable_user_id', $manager_lead != '' ? $manager_lead : get_option('current_manager'), $post_new);


            //MAIL
            $mailEvents = new WP_Query( [
                'post_type'     => 'notification',
                'post_status'   => 'draft',
                'posts_per_page'=> -1,

            ]);
            foreach ($mailEvents->posts as $event){
                $event_content = json_decode($event->post_content, true);

                if($event_content['enabled'] == false && $event_content['trigger'] == 'post/leads/added'){
                    $subject = $event_content['carriers']['email']['subject'];
                    $message = replaceTagsForLeadsStatusChange($post_id, $old_value, $value, $event_content['carriers']['email']['body']);

                    $manager_email = get_user_by('ID', get_field('manager', $post_id)['ID']);
                    if(!empty($manager_email)){
                        $event_content['carriers']['email']['recipients'][] = array(
                            'recipient' => $manager_email->user_email
                        );
                    }
	                $headers = "MIME-Version: 1.0". "\r\n";;
	                $headers .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	                $headers .= "From: MTRGROUP-SYSTEM <".get_option('admin_email').">";

                    foreach ($event_content['carriers']['email']['recipients'] as $recipient){
                        mail( $recipient['recipient'], $subject, $message, $headers );
                    }
                }
            }




        }
    }

    return $value;
}


add_action( 'wp_ajax_get_posts_and_acf', 'get_posts_and_acf_callback' );
add_action( 'wp_ajax_nopriv_get_posts_and_acf', 'get_posts_and_acf_callback' );
function get_posts_and_acf_callback() {

    $post_type = $_POST['post_type'];
    $per_page = $_POST['per_page'];
    $exclude = $_POST['exclude'];
    $search = $_POST['search'];
    $type = $_POST['type'];


    $args = array(
        'posts_per_page' => $per_page,
        'orderby'       => 'date',
        'order'         => 'DESC',
        'post_type'     => $post_type,
        'post__not_in'  => $exclude,
    );

    if(!empty($search)){
        $args['s'] = $search;
        $args['exact'] = true;
    }

    if(!empty($type) && $type != 'all'){
        $args['category__in'] = $type;
    }


    $temp_post = array();
    $posts = query_posts( $args );

    foreach ($posts as $key => $post){
        $temp_post[$key] = (array)$post;

        if(get_post_thumbnail_id( $post->ID )){
            $temp_post[$key]['post_image'] = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(800,400));
        }
        $temp_post[$key]['category'] = get_the_category($post->ID);
        $temp_post[$key]['acf'] = get_fields($post->ID);
    }
    $posts = $temp_post;

    echo json_encode($posts);

    wp_die();
}

add_action( 'wp_ajax_set_acf', 'set_acf_callback' );
add_action( 'wp_ajax_nopriv_set_acf', 'set_acf_callback' );
function set_acf_callback() {

    $post_id = $_POST['post_id'];
    $acf_name = $_POST['acf_name'];
    $acf_value = $_POST['acf_value'];

    update_field($acf_name, $acf_value, $post_id);

    echo json_encode(get_field($acf_name, $post_id));

    wp_die();
}

function getArrMenu ($id = null){
    if($id != null){

        $arMenu = array();
        $menus = wp_get_nav_menus();
        foreach ($menus as $menu){
            if($menu->term_id == $id){
                $arMenu = (array)$menu;
            }
        }
        if(!empty($arMenu)){
            $menuItems = wp_get_nav_menu_items( $id );
            $arMenu['items'] = $menuItems;

            return $arMenu;
        }

        return null;
    }

    return null;
}

function get_city_ru_nominative($city){
    $city = morphos\Russian\GeographicalNamesInflection::getCase($city, 'именительный');
    return $city;
}
function get_city_ru_genitive($city){
    $city = morphos\Russian\GeographicalNamesInflection::getCase($city, 'родительный');
    return $city;
}
function get_city_ru_dative($city){
    $city = morphos\Russian\GeographicalNamesInflection::getCase($city, 'дательный');
    return $city;
}
function get_city_ru_accusative($city){
    $city = morphos\Russian\GeographicalNamesInflection::getCase($city, 'винительный');
    return $city;
}
function get_city_ru_instrumental($city){
    $city = morphos\Russian\GeographicalNamesInflection::getCase($city, 'творительный');
    return $city;
}
function get_city_ru_prepositional($city){
    $city = morphos\Russian\GeographicalNamesInflection::getCase($city, 'предложный');
    return $city;
}


add_action('save_post','redirect_page', 10, 3);
function redirect_page($post_id, $post, $update){
    if($update == true){
        $type=get_post_type();
        if($type == 'recall'){
            update_field('is_new', 1, $post_id);
            $url=  admin_url().'edit.php?post_type='.$type;
            wp_redirect($url);
            exit();
        }
    }
}


add_action( 'rest_api_init', function(){

	$namespace = 'api/v1';

	$rout_cargo = '/cargo/search/';
	$rout_params_cargo = [
		'methods'  => 'GET',
		'callback' => 'cargo_search_callback',
	];
	if(!is_admin()){
		register_rest_route( $namespace, $rout_cargo, $rout_params_cargo );
	}


	$rout_countries = '/countries/search/';
	$rout_params_countries = [
		'methods'  => 'GET',
		'callback' => 'countries_search_callback',
	];
	if(!is_admin()){
		register_rest_route( $namespace, $rout_countries, $rout_params_countries );
	}
	
} );
function cargo_search_callback( WP_REST_Request $request ){

	$country_id = (int) $request['country_id'];
	$cargoNameSuggest = $request['query'];

	$answer = [
		'query' => 'Unit',
		'suggestions' => [],
	];
	
	if($country_id != null){
		$arCargos = null;
		$arCargoBySuggestions = null;

		if (empty($cargoNameSuggest)) {
			$args = array(
				'posts_per_page' => -1,
				'post_type' => 'type_cargos',
				'orderby' => 'title',
				'order'   => 'ASC',
				'post_status' => 'publish'
			);
			$cargos = new WP_Query( $args );
			if($cargos->post_count > 0){
				foreach ($cargos->posts as $post){
					$arCargos[] = (array)$post;
				}
			}

		}
		else {
			$cargoNameSuggest = stripslashes($cargoNameSuggest);
			$cargoNameSuggest = mb_strtolower($cargoNameSuggest, 'UTF-8');

			//cargos
			$args = array(
				'posts_per_page' => -1,
				'post_type' => 'type_cargos',
				'orderby' => 'title',
				's' => $cargoNameSuggest,
				'order'   => 'ASC',
				'post_status' => 'publish'
			);
			$cargos = new WP_Query( $args );
			if($cargos->post_count > 0){
				foreach ($cargos->posts as $cargo){
					$arCargos[] = (array)$cargo;
				}
			}

			//aliases
			$args = array(
				'taxonomy'      => 'aliases',
				'orderby'       => 'name',
				'order'         => 'ASC',
				'hide_empty'    => true,
				'search'        => $cargoNameSuggest,
				'update_term_meta_cache' => true,
			);
			$cargoBySuggestions = new WP_Term_Query( $args );
			$arCargoBySuggestions = array();
			if(!empty($cargoBySuggestions->terms)){
				foreach ($cargoBySuggestions->terms as $term){
					$arCargoBySuggestions[] = (array)$term;
				}
			}

		}

		if ($arCargoBySuggestions) {
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
								'value' => $country_id
							],
							[
								'key' => 'type',
								'value' => $cargosItem['ID']
							],
						]
					);
					$country = new WP_Query( $args );
					$arCountry = array();
					if(count($country->posts) == 1){
						$arCountry = (array)$country->post;
					}
					else if(count($country->posts) > 1){
						$arCountry = (array)$country->posts[array_key_first($country->posts)];
					}
					$country = array();

					if(!empty($arCountry['ID'])){
						$country['acf'] = get_fields($arCountry['ID']);
					}


					if (empty($country)) {
						continue;
					}
					$prices = array();
					if(empty($country['acf']['prices'])){
						$prices = null;
					}else{
						$prices['items'] = $country['acf']['prices'];
						$prices = json_encode($prices);
					}

					$variable_params = array();
					if(!empty($cargosItem['acf']['name_unit']) && !empty($cargosItem['acf']['unit']) && !empty($cargosItem['acf']['min']) && !empty($cargosItem['acf']['max'])){
						$variable_params['items'][] = array(
							'name_param' => $cargosItem['acf']['name_unit'],
							'unit'       => $cargosItem['acf']['unit'],
							'min_value'  => $cargosItem['acf']['min'],
							'max_value'  => $cargosItem['acf']['max']
						);
						$variable_params = json_encode($variable_params);
					}else{
						$variable_params = null;
					}


					$answer['suggestions'][] = ['value' => $term['name'],
                        'data' => [
                            'cargo_id' => $cargosItem['ID'],
                            'price_per_kilo' => $country['acf']['price_per_kilo'] ?? 0,
                            'need_cube_meter' => $country['acf']['need_cube_meter'] ?? 0,
                            'price_per_cube_meter' => $country['acf']['price_per_cube_meter'] ?? 0,
                            'prices' => $prices ?? 0,
                            'need_volume_and_weight' => $country['acf']['need_volume_and_weight'] ?? 0,
                            'variable_params' => $variable_params
                        ]
					];
				}


			}
		}

		if(!empty($arCargos)){
			foreach ($arCargos as $cargo) {
				$cargoAcf = get_fields($cargo['ID']);

				$country = new WP_Query( [
					'posts_per_page' => -1,
					'post_type' => 'type_cargo_country',
					'post_status' => 'publish',
					'meta_query' => [
						'relation' => 'AND',
						[
							'key' => 'country',
							'value' => $country_id
						],
						[
							'key' => 'type',
							'value' => $cargo['ID']
						],
					]
				] );

				if(count($country->posts) == 1){
					$country = $country->post;
				}
				else if(count($country->posts) > 1){
					$country = $country->posts[array_key_first($country->posts)];
				}

				$countryAcf = get_fields($country->ID);

				if (empty($country)) {
					continue;
				}
				$prices = array();
				if(empty($countryAcf['prices']) && !isset($countryAcf['prices'])){
					$prices = null;
				}
				else{
					$prices['items'][] = $countryAcf['prices'];
					$prices = json_encode($prices);
				}


				$variable_params = array(
					'items' => array()
				);
				if(!empty($cargoAcf['name_unit']) && !empty($cargoAcf['unit']) && !empty($cargoAcf['min']) && !empty($cargoAcf['max'])){
					$variable_params['items'][] = array(
						'name_param' => $cargoAcf['name_unit'],
						'unit'       => $cargoAcf['unit'],
						'min_value'  => $cargoAcf['min'],
						'max_value'  => $cargoAcf['max']
					);
					$variable_params = json_encode($variable_params);
				}else{
					$variable_params = null;
				}


				$answer['suggestions'][] = ['value' => $cargo['post_title'],
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

		return $answer;
	}else{
		return $answer;
	}
}
function countries_search_callback( WP_REST_Request $request ){
	$arCountries = array();
	$countryNameSuggest = $request['query'];
	$countryNameSuggest = stripslashes($countryNameSuggest);

	$answer = [
		'query'=> 'Unit',
		'suggestions' => []
	];

	if($countryNameSuggest != ''){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'countries',
			'orderby' => 'title',
			's'     => $countryNameSuggest,
			'sentence' => true,
			'order'   => 'ASC',
			'post_status' => 'publish',
			'post_parent' => 0,
			'post__not_in' => array(get_option('main_country'))
		);

	}else{
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'countries',
			'orderby' => 'title',
			'order'   => 'ASC',
			'post_status' => 'publish',
			'post_parent' => 0,
			'post__not_in' => array(get_option('main_country'))
		);

	}
	$countries = new WP_Query( $args );
	if( $countries->post_count > 0){
		foreach ($countries->posts as $post){
			$arCountries[] = (array)$post;
		}
	}
	foreach ($arCountries as $country) {
		$answer['suggestions'][] = ['value' => $country['post_title'], 'data' => $country['ID']];
	}

	return $answer;
}

//поиск только по заголовкам записей start
function wph_search_by_title($search, $wp_query) {
	global $wpdb;
	if (empty($search)) return $search;

	$q = $wp_query->query_vars;
	$n = !empty($q['exact']) ? '' : '%';
	$search = $searchand = '';

	foreach ((array) $q['search_terms'] as $term) {
		$term = esc_sql(like_escape($term));
		$search.="{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
		$searchand = ' AND ';
	}

	if (!empty($search)) {
		$search = " AND ({$search}) ";
		if (!is_user_logged_in())
			$search .= " AND ($wpdb->posts.post_password = '') ";
	}
	return $search;
}
add_filter('posts_search', 'wph_search_by_title', 500, 2);
//поиск только по заголовкам записей end

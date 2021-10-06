<?php

use WP_CLI\CommandWithDBObject;
use WP_CLI\Entity\Utils as EntityUtils;
use WP_CLI\Fetchers\Post as PostFetcher;
use WP_CLI\Fetchers\User as UserFetcher;
use WP_CLI\Utils;

class Post_Term extends \WP_CLI_Command{

    public function insert( $args, $assoc_args ){

        $mysqli = new mysqli("mysql", "admin", "admin", "laravel");
        $mysqli->set_charset("utf8mb4");
        $result = $mysqli->query("SELECT countries.name as countries_name, type_cargos.name as type_name, price_per_kilo, need_cube_meter, price_per_cube_meter, prices, need_volume_and_weight FROM type_cargo_country INNER JOIN type_cargos ON type_cargos.id=`type_cargo_country`.`type_cargo_id` INNER JOIN countries ON countries.id=`type_cargo_country`.`country_id`");
        if($result->num_rows>0){

            WP_CLI::log('Count rows '.$result->num_rows);
            $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', $result->fetch_all());
            while ($row = $result->fetch_object()) {

                $countries = new WP_Query([
                    'post_type' => 'countries',
                    'title' => $row->countries_name
                ]);
                $country_name = $countries->posts[0]->post_title;
                $country_id = $countries->posts[0]->ID;

                $type_cargos = new WP_Query([
                    'post_type' => 'type_cargos',
                    'title' => $row->type_name
                ]);
                $type_cargo_name = $type_cargos->posts[0]->post_title;
                $type_cargo_id = $type_cargos->posts[0]->ID;

                $post_data = array(
                    'post_title'    => $type_cargo_name. ' ('.$country_name.')',
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_type'     => 'type_cargo_country',
                );

                $post_id = wp_insert_post( $post_data );

                update_field('type', $type_cargo_id, $post_id);
                update_field('country', $country_id, $post_id);

                update_field('price_per_kilo', $row->price_per_kilo, $post_id);
                update_field('need_cube_meter', $row->need_cube_meter, $post_id);
                if($row->price_per_cube_meter != null){
                    update_field('price_per_cube_meter', $row->price_per_cube_meter, $post_id);
                }
                if($row->prices != null){
                    $prices = json_decode($row->prices, true);
                    $field_key = "prices";
                    $value = array();
                    foreach ($prices['items'] as $price){
                        $value[] = array(
                            "up_to_weight"	=> $price['up_to_weight'],
                            "weight_price"	=> $price['weight_price'],
                            "up_to_volume"	=> $price['up_to_volume'],
                            "volume_price"	=> $price['volume_price'],
                        );
                    }
                    update_field( $field_key, $value, $post_id );
                }
                update_field('need_volume_and_weight', $row->need_volume_and_weight, $post_id);


                $progress->tick();
            }
            $progress->finish();
            WP_CLI::success('Post imported!!!!');
        }
    }

    public function remove( $args, $assoc_args ){
        $posts = new WP_Query([
            'post_type' => 'type_cargo_country',
            'posts_per_page' => -1,
        ]);
        WP_CLI::log('Count rows '.$posts->post_count);
        $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', $posts->posts);
        for ($i = 0; $i < $posts->post_count; $i++){
            wp_delete_post($posts->posts[$i]->ID, true);
            $progress->tick();
        }
        $progress->finish();
        WP_CLI::success('Post removed!!!!');
    }

    public function insertTrackNumbers( $args, $assoc_args ){

        $mysqli = new mysqli("mysql", "admin", "admin", "laravel");
        $mysqli->set_charset("utf8mb4");
        $result = $mysqli->query("SELECT * FROM track_statuses");
        if($result->num_rows>0){

            WP_CLI::log('Count rows '.$result->num_rows);
            while ($row = $result->fetch_object()) {

                $track_descriptors  = new WP_Query([
                    'post_type' => 'track_descriptors',
                    'title' => $row->status
                ]);
                $track_descriptor_id = $track_descriptors->posts[0]->ID;

                $post_data = array(
                    'post_title'    => $row->track_number,
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_type'     => 'track_statuses',
                    'post_date'     => $row->updated
                );
                $post_id = wp_insert_post( $post_data );
                update_field('status', $track_descriptor_id, $post_id);

            }
            WP_CLI::success('Post imported!!!!');
        }
    }

    public function removeTrackNumbers( $args, $assoc_args ){
        $posts = new WP_Query([
            'post_type' => 'track_statuses',
            'posts_per_page' => -1,
        ]);
        WP_CLI::log('Count rows '.$posts->post_count);
        $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', $posts->posts);
        for ($i = 0; $i < $posts->post_count; $i++){
            wp_delete_post($posts->posts[$i]->ID, true);
            $progress->tick();
        }
        $progress->finish();
        WP_CLI::success('Post removed!!!!');
    }

    public function addTitleAndDesc($args, $assoc_args){
        $url_csv = $_SERVER['DOCUMENT_ROOT'].'/blog_mtr.csv';
        $csv = file_get_contents($url_csv);

        $csv = $this->csvstring_to_array( $csv );
        array_filter($csv, function($v){return array_filter($v) == array();});

//        $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', $csv);
//
//        foreach ($csv as $item){
//            if($item[0] != '' && $item[1] != ''){
//                WP_CLI::log(print_r($item));
//            }
//
//            $progress->tick();
//        }
//        $progress->finish();

//        $csv_f = $csv[0];
//        if($csv_f[0] != '' && $csv_f[1] != ''){
//            $posts_arr = aioseo()->db->start( 'aioseo_posts as ap' )
//                     ->select( 'ap.*' )
//                     ->join( 'posts as p', 'ap.post_id = p.ID' )
//                     ->where( 'p.post_title', $csv_f[1] )
//                     ->run()
//                     ->result();
//
//
//        }


    }

    function csvstring_to_array($string, $skip_rows = 0, $separatorChar = ';', $enclosureChar = '"', $newlineChar = "\n") {

        $array = array();
        $size = strlen($string);
        $columnIndex = 0;
        $rowIndex = 0;
        $fieldValue="";
        $isEnclosured = false;
        for($i=0; $i<$size;$i++) {

            $char = $string{$i};
            $addChar = "";

            if($isEnclosured) {
                if($char==$enclosureChar) {

                    if($i+1<$size && $string{$i+1}==$enclosureChar){
                        // escaped char
                        $addChar=$char;
                        $i++; // dont check next char
                    }else{
                        $isEnclosured = false;
                    }
                }else {
                    $addChar=$char;
                }
            }else {
                if($char==$enclosureChar) {
                    $isEnclosured = true;
                }else {

                    if($char==$separatorChar) {

                        $array[$rowIndex][$columnIndex] = $fieldValue;
                        $fieldValue="";

                        $columnIndex++;
                    }elseif($char==$newlineChar) {
                        echo $char;
                        $array[$rowIndex][$columnIndex] = $fieldValue;
                        $fieldValue="";
                        $columnIndex=0;
                        $rowIndex++;
                    }else {
                        $addChar=$char;
                    }
                }
            }
            if($addChar!=""){
                $fieldValue.=$addChar;

            }
        }

        if($fieldValue) { // save last field
            $array[$rowIndex][$columnIndex] = $fieldValue;
        }


        /**
         * Skip rows.
         * Returning empty array if being told to skip all rows in the array.
         */
        if ($skip_rows > 0) {
            if (count($array) == $skip_rows)
                $array = array();
            elseif (count($array) > $skip_rows)
                $array = array_slice($array, $skip_rows);

        }

        return $array;
    }



    public function actualizeDataRecall($args, $assoc_args) {

        $mysqli = new mysqli("mysql", "admin", "admin", "laravel");
        $mysqli->set_charset("utf8mb4");
        $result = $mysqli->query("SELECT * FROM recalls");
        if($result->num_rows>0){

            WP_CLI::log('Count rows '.$result->num_rows);
            while ($row = $result->fetch_object()) {


                $name = $row->name;
                $phone = $row->contact;
                $comment = $row->comment;
                $is_new = $row->is_new;

                $post_data = array(
                    'post_status'   => 'publish',
                    'post_type'     => 'recall',
                    'post_author'   => 1,
                    'post_date'     => $row->created_at
                );
                $post_id = wp_insert_post( $post_data );

                if(!empty($name)){
                    update_field('name_client', $name, $post_id);
                }
                if(!empty($phone)){
                    update_field('contact', $phone, $post_id);
                }
                if(!empty($comment)){
                    update_field('comment', $comment, $post_id);
                }
                update_field('is_new', (int)$is_new, $post_id);

                $user_id_for = null;

                if($row->user_id != NULL){
                    $userRecall = $mysqli->query("SELECT * FROM users WHERE id = ".$row->user_id);
                    $user_obj = $userRecall->fetch_object();

                    $user_by_email = email_exists($user_obj->email);
                    $user_by_login = username_exists($user_obj->surname);

                    if($user_by_email == false && $user_by_login == false){
                        $random_password = wp_generate_password( 12 );
                        $user_id = wp_insert_user([
                            'user_login' => $user_obj->surname,
                            'user_pass'  => $random_password,
                            'user_email' => $user_obj->email,
                            'first_name' => $user_obj->name,
                            'nickname'   => $user_obj->surname,
                            'role'       => 'manager'
                        ]);
                        if( ! is_wp_error( $user_id ) ){
                            $user_id_for = $user_id;
                        }
                        else {
                            $user_id_for = get_option('current_manager');
                            WP_CLI::error($user_id->get_error_message());
                        }
                    }
                    else{

                         if($user_by_email != false){
                             $user_id_for = $user_by_email;
                         }
                         elseif ($user_by_login != false){
                             $user_id_for = $user_by_login;
                         }
                    }

                }
                update_field('manager', $user_id_for, $post_id);

            }
            WP_CLI::success('Post imported!!!!');
        }

    }
    public function removeRecall() {
        $posts = new WP_Query([
            'post_type' => 'recall',
            'posts_per_page' => -1,
        ]);
        WP_CLI::log('Count rows '.$posts->post_count);
        $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', $posts->posts);
        for ($i = 0; $i < $posts->post_count; $i++){
            wp_delete_post($posts->posts[$i]->ID, true);
            $progress->tick();
        }
        $progress->finish();
        WP_CLI::success('Post removed!!!!');
    }

    public function removeData( $args, $assoc_args ){
        $posts = new WP_Query([
            'post_type' => 'leads',
            'posts_per_page' => -1,
        ]);
        WP_CLI::log('Count rows '.$posts->post_count);
        $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', (int) count($posts->posts) );
        for ($i = 0; $i < $posts->post_count; $i++){
            wp_delete_post($posts->posts[$i]->ID, true);
            $progress->tick();
        }
        $progress->finish();


        $posts = new WP_Query([
            'post_type' => 'orders',
            'posts_per_page' => -1,
        ]);
        WP_CLI::log('Count rows '.$posts->post_count);
        $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', (int) count($posts->posts) );
        for ($i = 0; $i < $posts->post_count; $i++){
            wp_delete_post($posts->posts[$i]->ID, true);
            $progress->tick();
        }
        $progress->finish();


        $posts = new WP_Query([
            'post_type' => 'leads_in_process',
            'posts_per_page' => -1,
        ]);
        WP_CLI::log('Count rows '.$posts->post_count);
        $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', (int) count($posts->posts) );
        for ($i = 0; $i < $posts->post_count; $i++){
            wp_delete_post($posts->posts[$i]->ID, true);
            $progress->tick();
        }
        $progress->finish();

        WP_CLI::success('Post removed!');
    }
    public function actualizeLeads( $args, $assoc_args ) {

        WP_CLI::log('Starting leads importing');
        $mysqli = new mysqli("mysql", "admin", "admin", "laravel");
        $mysqli->set_charset("utf8mb4");
        $result = $mysqli->query("SELECT * FROM leads");

        $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', (int) $result->num_rows );
        if($result->num_rows>0){
            WP_CLI::log('Count rows '.$result->num_rows);
            while ($row = $result->fetch_object()) {

                $post_data = array(
                    'post_status'   => 'publish',
                    'post_type'     => 'leads',
                    'post_author'   => 1,
                    'post_date'     => $row->created_at
                );
                $post_id = wp_insert_post( $post_data );

                $leadDATA = array();
                $leadDATA['lead_id'] = $post_id;
                $leadDATA['cargo_volume'] = $row->volume;
                $leadDATA['cargo_weight'] = $row->weight;
                $leadDATA['need_container'] = $row->need_container;
                $leadDATA['estimated_shipping_cost'] = $row->price_per_kilo;
                $leadDATA['client_name'] = $row->name_customer;
                $leadDATA['phone_text'] = $row->phone;
                $leadDATA['email'] = $row->email;
                $leadDATA['from_city'] = $row->city_from;
                $leadDATA['to_city'] = $row->city_to;


                update_field('cargo_volume', $row->volume, $post_id);
                update_field('cargo_weight', $row->weight, $post_id);
                update_field('need_container', $row->need_container, $post_id);
                update_field('estimated_shipping_cost', $row->price_per_kilo, $post_id);
                update_field('client_name', $row->name_customer, $post_id);
                update_field('phone_text', $row->phone, $post_id);
                update_field('email', $row->email, $post_id);
                update_field('from_city', $row->city_from, $post_id);
                update_field('to_city', $row->city_to, $post_id);

                if($row->type_cargo_id != NULL){
                    $typeCargo = $mysqli->query("SELECT * FROM type_cargos WHERE id = ".$row->type_cargo_id);
                    $typeCargo_obj = $typeCargo->fetch_object();

                    $type_cargos = new WP_Query([
                        'post_type' => 'type_cargos',
                        'title' => $typeCargo_obj->name
                    ]);

                    $leadDATA['cargo_type'] = $type_cargos->post->ID;
                    update_field('cargo_type', $type_cargos->post->ID, $post_id);
                }
                if($row->country_id != NULL){
                    $countries = $mysqli->query("SELECT * FROM countries WHERE id = ".$row->country_id);
                    $countries_obj = $countries->fetch_object();

                    $countries = new WP_Query([
                        'post_type' => 'countries',
                        'title' => $countries_obj->name
                    ]);
                    $leadDATA['from_country'] = $countries->post->ID;
                    update_field('from_country', $countries->post->ID, $post_id);
                }
                if($row->variable_data != NULL){
                    $variable = json_decode($row->variable_data, true);
                    $variable = $variable[0];

                    $leadDATA['name_variable'] = $variable['name'];
                    $leadDATA['value_variable'] = $variable['value'];

                    update_field('name_variable', $variable['name'], $post_id);
                    update_field('count_variable', $variable['value'], $post_id);
                }
                if($row->user_type_cargo != NULL){
                    $leadDATA['cargo_type_client'] = $row->user_type_cargo;
                    update_field('cargo_type_client', $row->user_type_cargo, $post_id);
                }
                if($row->referer != NULL){
                    $leadDATA['referer'] = $row->referer;
                    update_field('referer', $row->referer, $post_id);
                }

                if($row->store_id != NULL){
                    $stores = $mysqli->query("SELECT * FROM stores WHERE id = ".$row->store_id);
                    $stores_obj = $stores->fetch_object();
                    $stores = new WP_Query([
                        'post_type' => 'stores',
                        'title' => $stores_obj->name
                    ]);
                    $leadDATA['from_storage'] = $stores->post->ID;
                    update_field('from_storage', $stores->post->ID, $post_id);
                }
                if($row->delivery_routes_id != NULL){
                    $delivery_routes = $mysqli->query("SELECT * FROM delivery_routes WHERE id = ".$row->delivery_routes_id);
                    $delivery_routes_obj = $delivery_routes->fetch_object();
                    $delivery_routes = new WP_Query([
                        'post_type' => 'delivery_route',
                        'title' => $delivery_routes_obj->name
                    ]);
                    $leadDATA['delivery_route'] = $delivery_routes->post->ID;
                    update_field('delivery_route', $delivery_routes->post->ID, $post_id);
                }
                if($row->lead_status_id != NULL){

                    /** @var INT $status_id
                     *  ID записи статуса с Wordpress
                     */
                    $status_id = $this->get_new_id_status_from_old_id( $mysqli, $row->lead_status_id );

                    $result_in_process = $mysqli->query("SELECT * FROM `lead_statuses_in_process` WHERE lead_id = ".$row->id);
                    if($result_in_process->num_rows>0){
                        while ($row_in = $result_in_process->fetch_object()) {

                            $post_new = wp_insert_post([
                                'post_status'   => 'publish',
                                'post_type'     => 'leads_in_process',
                                'post_author'   => 1,
                                'post_date'     => $row_in->start_executable
                            ]);
                            update_field('lead', $post_id, $post_new);
                            update_field('status', $this->get_new_id_status_from_old_id($mysqli, $row_in->lead_status_id), $post_new);
                            update_field('start_executable', $row_in->start_executable, $post_new);
                            update_field('is_success_executable', $row_in->is_success_executable, $post_new);
                            update_field('executable_user_id', $this->get_new_id_user_from_old_id($mysqli, $row_in->executable_user_id), $post_new);


                        }
                    }

                    $leadDATA['status'] = $status_id;
                    update_field('status', $status_id, $post_id);
                }

                if($row->customer_id != NULL){
                    $is_user = $this->get_new_id_user_from_old_id($mysqli, $row->customer_id);

                    if($is_user == null){

                        $user_name = '';
                        if($row->name_customer != NULL && $row->name_customer != '' && strlen($row->name_customer) >= 4){
                            $user_name = $this->transliterate(str_replace(' ', '_', strtolower($row->name_customer)));
                        }
                        else{
                            $user_name = $this->generate_unique_username();
                        }

                        $user_email = '';
                        if($row->email != NULL){
                            $user_email = $row->email;
                        }
                        else{
                            $user_email = $user_name.'@mtrgroup.ru';
                        }
                        $phone = '';
                        if($row->phone != NULL){
                            $phone = $row->phone;
                        }

                        $random_password = wp_generate_password( 12 );
                        $user_id = wp_insert_user([
                            'user_login' => strtolower($user_name),
                            'user_pass'  => $random_password,
                            'user_email' => $user_email,
                            'first_name' => $row->name_customer != '' ? $row->name_customer : 'Клиент',
                            'nickname'   => strtolower($user_name),
                            'role'       => 'client'
                        ]);

                        if( ! is_wp_error( $user_id ) ){
                            $leadDATA['customer_id'] = $user_id;
                            update_field('customer_id', $user_id, $post_id);
                            if(!empty($phone)){
                                update_field('telephone', $phone, 'user_'.$user_id);
                            }
                            update_field('type', 'ur', 'user_'.$user_id);
                        }
                        else {
                            WP_CLI::error($user_id->get_error_message());
                        }
                    }
                    else{
                        $leadDATA['customer_id'] = $is_user;
                        update_field('customer_id', $is_user, $post_id);
                    }
                }

                $user_id_for = null;
                if($row->user_id != NULL){
                    $is_user = $this->get_new_id_user_from_old_id($mysqli, $row->user_id);

                    if($is_user == NULL){
                        $userRecall = $mysqli->query("SELECT * FROM users WHERE id = ".$row->user_id);
                        $user_obj = $userRecall->fetch_object();

                        $random_password = wp_generate_password( 12 );
                        $user_id = wp_insert_user([
                            'user_login' => $user_obj->surname,
                            'user_pass'  => $random_password,
                            'user_email' => $user_obj->email,
                            'first_name' => $user_obj->name,
                            'nickname'   => $user_obj->surname,
                            'role'       => 'manager'
                        ]);
                        if( ! is_wp_error( $user_id ) ){
                            $user_id_for = $user_id;
                        }
                        else {
                            $user_id_for = get_option('current_manager');
                            WP_CLI::error($user_id->get_error_message());
                        }
                    }
                    else{
                        $user_id_for = $is_user;
                    }

                    $leadDATA['manager'] = $user_id_for;
                    update_field('manager', $user_id_for, $post_id);
                }

                $orders_result = $mysqli->query("SELECT * FROM orders WHERE lead_id = ".$row->id);
                $orders_result_obj = $orders_result->fetch_object();
                if($orders_result_obj != null){

                    $order_id = wp_insert_post( [
                        'post_status'   => 'publish',
                        'post_type'     => 'orders',
                        'post_author'   => 1,
                        'post_date'     => $orders_result_obj->created_at
                    ] );

                    update_field('cargo_volume', $leadDATA['cargo_volume'], $order_id);
                    update_field('cargo_weight', $leadDATA['cargo_weight'], $order_id);
                    update_field('cargo_type', $leadDATA['cargo_type'], $order_id);
                    update_field('need_container', $leadDATA['need_container'], $order_id);
                    update_field('from_country', $leadDATA['from_country'], $order_id);
                    update_field('from_city', $leadDATA['from_city'], $order_id);
                    update_field('to_city', $leadDATA['to_city'], $order_id);
                    update_field('from_storage', $leadDATA['from_storage'], $order_id);
                    update_field('delivery_route', $leadDATA['delivery_route'], $order_id);
                    update_field('estimated_shipping_cost', $leadDATA['estimated_shipping_cost'], $order_id);
                    update_field('client', $leadDATA['customer_id'], $order_id);
                    update_field('manager', $leadDATA['manager'], $order_id);
                    if(!empty($leadDATA['name_variable'])){
                        update_field('name_variable', $leadDATA['name_variable'], $order_id);
                    }
                    if(!empty($orderDATA['count_variable'])){
                        update_field('count_variable', $leadDATA['count_variable'], $order_id);
                    }
                    update_field('lead_id', $leadDATA['lead_id'], $order_id);

                }

                $progress->tick();
            }
        }
        $progress->finish();
        WP_CLI::success('Lead imported');


        WP_CLI::log('Other orders importing started');
        $orders_others_result = $mysqli->query("SELECT * FROM orders WHERE lead_id iS NULL");
        $progress = \WP_CLI\Utils\make_progress_bar('Foreaching array from db ', (int) $orders_others_result->num_rows );
        if($orders_others_result->num_rows>0){
            while ($row = $orders_others_result->fetch_object()) {

                $order_id = wp_insert_post( [
                    'post_status'   => 'publish',
                    'post_type'     => 'orders',
                    'post_author'   => 1,
                ] );

                $orderDATA = array();
                $orderDATA['cargo_volume'] = $row->volume;
                $orderDATA['cargo_weight'] = $row->weight;
                $orderDATA['need_container'] = $row->need_container;
                $orderDATA['estimated_shipping_cost'] = $row->price_per_kilo;
                $orderDATA['from_city'] = $row->city_from;
                $orderDATA['to_city'] = $row->city_to;


                if($row->type_cargo_id != NULL){
                    $typeCargo = $mysqli->query("SELECT * FROM type_cargos WHERE id = ".$row->type_cargo_id);
                    $typeCargo_obj = $typeCargo->fetch_object();

                    $type_cargos = new WP_Query([
                        'post_type' => 'type_cargos',
                        'title' => $typeCargo_obj->name
                    ]);

                    $orderDATA['cargo_type'] = $type_cargos->post->ID;
                }
                if($row->country_id != NULL){
                    $countries = $mysqli->query("SELECT * FROM countries WHERE id = ".$row->country_id);
                    $countries_obj = $countries->fetch_object();

                    $countries = new WP_Query([
                        'post_type' => 'countries',
                        'title' => $countries_obj->name
                    ]);
                    $orderDATA['from_country'] = $countries->post->ID;
                }
                if($row->store_id != NULL){
                    $stores = $mysqli->query("SELECT * FROM stores WHERE id = ".$row->store_id);
                    $stores_obj = $stores->fetch_object();
                    $stores = new WP_Query([
                        'post_type' => 'stores',
                        'title' => $stores_obj->name
                    ]);
                    $orderDATA['from_storage'] = $stores->post->ID;
                }
                if($row->delivery_routes_id != NULL){
                    $delivery_routes = $mysqli->query("SELECT * FROM delivery_routes WHERE id = ".$row->delivery_routes_id);
                    $delivery_routes_obj = $delivery_routes->fetch_object();
                    $delivery_routes = new WP_Query([
                        'post_type' => 'delivery_route',
                        'title' => $delivery_routes_obj->name
                    ]);
                    $orderDATA['delivery_routes'] = $delivery_routes->post->ID;
                }
                if($row->order_status_id != NULL){
                    $orderDATA['status'] = $this->get_new_id_status_order_from_old_id( $mysqli, $row->order_status_id );
                }
                if($row->customer_id != NULL){
                    $is_user = $this->get_new_id_user_from_old_id($mysqli, $row->customer_id);

                    if($is_user == null){

                        $user_name = '';
                        if($row->name_customer != NULL && $row->name_customer != '' && strlen($row->name_customer) >= 4){
                            $user_name = $this->transliterate(str_replace(' ', '_', strtolower($row->name_customer)));
                        }
                        else{
                            $user_name = $this->generate_unique_username();
                        }

                        $user_email = '';
                        if($row->email != NULL){
                            $user_email = $row->email;
                        }
                        else{
                            $user_email = $user_name.'@mtrgroup.ru';
                        }
                        $phone = '';
                        if($row->phone != NULL){
                            $phone = $row->phone;
                        }

                        $random_password = wp_generate_password( 12 );
                        $user_id = wp_insert_user([
                            'user_login' => strtolower($user_name),
                            'user_pass'  => $random_password,
                            'user_email' => $user_email,
                            'first_name' => $row->name_customer != '' ? $row->name_customer : 'Клиент',
                            'nickname'   => strtolower($user_name),
                            'role'       => 'client'
                        ]);

                        if( ! is_wp_error( $user_id ) ){
                            $leadDATA['customer_id'] = $user_id;
                            if(!empty($phone)){
                                update_field('telephone', $phone, 'user_'.$user_id);
                            }
                            update_field('type', 'ur', 'user_'.$user_id);
                        }
                        else {
                            WP_CLI::error($user_id->get_error_message());
                        }
                    }
                    else{
                        $leadDATA['customer_id'] = $is_user;
                    }
                }
                if($row->user_id != NULL){
                    $user_id_for = null;
                    $is_user = $this->get_new_id_user_from_old_id($mysqli, $row->user_id);

                    if($is_user == NULL){
                        $userRecall = $mysqli->query("SELECT * FROM users WHERE id = ".$row->user_id);
                        $user_obj = $userRecall->fetch_object();

                        $random_password = wp_generate_password( 12 );
                        $user_id = wp_insert_user([
                            'user_login' => $user_obj->surname,
                            'user_pass'  => $random_password,
                            'user_email' => $user_obj->email,
                            'first_name' => $user_obj->name,
                            'nickname'   => $user_obj->surname,
                            'role'       => 'manager'
                        ]);
                        if( ! is_wp_error( $user_id ) ){
                            $user_id_for = $user_id;
                        }
                        else {
                            WP_CLI::error($user_id->get_error_message());
                        }
                    }
                    else{
                        $user_id_for = $is_user;
                    }

                    $leadDATA['manager'] = $user_id_for;
                }


                if(!empty($orderDATA['cargo_volume'])){
                    update_field('cargo_volume', $orderDATA['cargo_volume'], $order_id);
                }
                if (!empty($orderDATA['cargo_weight'])){
                    update_field('cargo_weight', $orderDATA['cargo_weight'], $order_id);
                }
                if(!empty($orderDATA['cargo_type'])){
                    update_field('cargo_type', $orderDATA['cargo_type'], $order_id);
                }
                if(!empty($orderDATA['need_container'])){
                    update_field('need_container', $orderDATA['need_container'], $order_id);
                }
                if(!empty($orderDATA['from_country'])){
                    update_field('from_country', $orderDATA['from_country'], $order_id);
                }
                if(!empty($orderDATA['from_city'])){
                    update_field('from_city', $orderDATA['from_city'], $order_id);
                }
                if(!empty($orderDATA['to_city'])){
                    update_field('to_city', $orderDATA['to_city'], $order_id);
                }
                if(!empty($orderDATA['from_storage'])){
                    update_field('from_storage', $orderDATA['from_storage'], $order_id);
                }
                if(!empty($orderDATA['delivery_route'])){
                    update_field('delivery_route', $orderDATA['delivery_route'], $order_id);
                }
                if(!empty($orderDATA['estimated_shipping_cost'])){
                    update_field('estimated_shipping_cost', $orderDATA['estimated_shipping_cost'], $order_id);
                }
                if(!empty($orderDATA['client'])){
                    update_field('client', $orderDATA['customer_id'], $order_id);
                }
                if (!empty($orderDATA['manager'])){
                    update_field('manager', $orderDATA['manager'], $order_id);
                }
                if(!empty($orderDATA['name_variable'])){
                    update_field('name_variable', $orderDATA['name_variable'], $order_id);
                }
                if(!empty($orderDATA['count_variable'])){
                    update_field('count_variable', $orderDATA['count_variable'], $order_id);
                }

                $progress->tick();
            }
        }
        $progress->finish();
        WP_CLI::success('Other orders imported successfully');
    }

    public function generate_unique_username() {
        $user_name = 'customer_'.random_int(0, PHP_INT_MAX);
        if(username_exists($user_name) == false){
            return $user_name;
        }else{
            $this->generate_unique_username();
        }
    }
    public function transliterate($textcyr = null, $textlat = null) {
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


    /**
     * @param mysqli $mysqli
     * @param $lead_status_id
     */
    public function get_new_id_status_from_old_id( mysqli $mysqli, $lead_status_id ) {
        $status_for_lead_db = $mysqli->query( "SELECT * FROM `lead_statuses` WHERE id = " . $lead_status_id );
        $status_for_lead_res = $status_for_lead_db->fetch_object();
        $status_for_lead    = ( new WP_Query( [
            'post_type' => 'statuses_for_leads',
            'title'     => $status_for_lead_res->name
        ] ) )->post;

        return $status_for_lead->ID;
    }

    public function get_new_id_status_order_from_old_id( mysqli $mysqli, $order_status_id ) {
        $status_for_lead_db = $mysqli->query( "SELECT * FROM `order_statuses` WHERE id = " . $order_status_id );
        $status_for_lead_res = $status_for_lead_db->fetch_object();
        $status_for_lead    = ( new WP_Query( [
            'post_type' => 'statuses_for_order',
            'title'     => $status_for_lead_res->name
        ] ) )->post;

        return $status_for_lead->ID;
    }

    public function get_new_id_user_from_old_id( mysqli $mysqli, $old_id ) {
            $user_laravel = $mysqli->query("SELECT * FROM users WHERE id = ".$old_id);
            $user_obj = $user_laravel->fetch_object();
            $user_by_email = email_exists($user_obj->email);
            $user_by_login = username_exists($user_obj->surname);

            $user_id_new = null;
            if($user_by_email != false){
                $user_id_new = $user_by_email;
            }
            elseif ($user_by_login != false){
                $user_id_new = $user_by_login;
            }

            return $user_id_new;
    }


}

WP_CLI::add_command( 'post-insert', 'Post_Term' );

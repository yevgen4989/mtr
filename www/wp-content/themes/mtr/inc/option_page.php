<?php
add_action( 'admin_menu', 'site_option_page' );
function site_option_page() {
    add_options_page( 'Настройки сайта', 'Настройки сайта', 'manage_options', 'site_option', 'site_options_page_output' );
}

function site_options_page_output() {
    ?>

    <div class="wrap">
        <h1><?php echo get_admin_page_title() ?></h1>
        <form action="options.php" method="POST">
            <?php
            settings_fields( 'site_option_group' );
            do_settings_sections( 'site_option_page' );
            submit_button();
            ?>
        </form>
    </div>

    <?php
}

add_action( 'admin_init', 'site_option_settings' );
function site_option_settings() {
    register_setting( 'site_option_group', 'site_option_name' );

    //Section
    add_settings_section( 'section_option', 'Основные настройки', '', 'site_option_page' );

    //Fields
    register_setting( 'site_option_group', 'email_to_message_event' );
    add_settings_field( 'email_to_message_event', 'Почта на отправку событий', 'email_to_message_event_callback_function', 'site_option_page', 'section_option',
        array(
            'id'          => 'email_to_message_event',
            'option_name' => 'email_to_message_event'
        )
    );
    register_setting( 'site_option_group', 'main_country' );
    add_settings_field( 'main_country', 'Главная страна', 'main_country_callback_function', 'site_option_page', 'section_option',
        array(
            'id'          => 'main_country',
            'option_name' => 'main_country'
        )
    );

    register_setting( 'site_option_group', 'page_delivery' );
    add_settings_field( 'page_delivery', 'Страница Международной доставки', 'page_delivery_callback_function', 'site_option_page', 'section_option', array(
            'id'          => 'page_delivery',
            'option_name' => 'page_delivery'
        )
    );
    register_setting( 'site_option_group', 'current_manager' );
    add_settings_field( 'current_manager', 'Текущий менеджер', 'current_manager_callback_function', 'site_option_page', 'section_option', array(
            'id'          => 'current_manager',
            'option_name' => 'current_manager'
        )
    );

}
function email_to_message_event_callback_function ( $val ){
    $id          = $val['id'];
    $option_name = $val['option_name'];
    ?>
    <input id="<? echo $id ?>" type="text" value="<?=esc_attr( get_option( $option_name ) )?>" name="<? echo $option_name ?>" >
    <?
}
function current_manager_callback_function( $val ) {
    $id          = $val['id'];
    $option_name = $val['option_name'];
    ?>
    <input readonly id="<? echo $id ?>" type="text" value="<?=esc_attr( get_option( $option_name ) )?>" name="<? echo $option_name ?>" >
    <?
}
function main_country_callback_function( $val ) {
    $id          = $val['id'];
    $option_name = $val['option_name'];

    $posts = get_posts( array(
        'numberposts' => - 1,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'countries',
        'post_parent' => 0,
    ) );
    ?>

    <select name="<? echo $option_name ?>" id="<? echo $id ?>">
        <?
        foreach ( $posts as $post ) {
            setup_postdata( $post ); ?>
            <option value="<?= $post->ID ?>" <? if ( $post->ID == esc_attr( get_option( $option_name ) ) ) {
                echo 'selected="selected"';
            } ?>><?= $post->post_title ?></option>
        <?
        }
        wp_reset_postdata();
        ?>
    </select>
    <?
}
function page_delivery_callback_function( $val ) {
    $id          = $val['id'];
    $option_name = $val['option_name'];
    $pages       = new WP_Query;
    $pages       = $pages->query( array(
        'post_type' => 'page'
    ) );
    ?>
    <select name="<? echo $option_name ?>" id="<? echo $id ?>">
        <?
        foreach ( $pages as $pst ) {
            ?>
            <option
                value="<?= esc_html( $pst->ID ); ?>" <? if ( esc_html( $pst->ID ) == esc_attr( get_option( $option_name ) ) ) {
                echo 'selected="selected"';
            } ?>><?= esc_html( $pst->post_title ); ?></option>
        <?
        }
        ?>
    </select>
    <?
}?>

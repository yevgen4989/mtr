<?
$info = get_fields( 'info' );
wp_footer();

get_template_part('include/recall');
?>

<footer>
    <? if ( ! empty( $info['schedule']['hours'] ) ) { ?>
        <div class="footer__schedule-line footer__schedule-line_mobile">
            Рабочее время:<br>
            <?= $info['schedule']['hours'] ?>
        </div>
    <? } ?>

    <div class="footer__menu-line">
        <div class="footer__menu-line__item">
            <div class="footer__logo-line">
                <? the_custom_logo() ?>
            </div>
        </div>

        <div class="footer__menu-line__item">
            <ul class="list list_vertical">
                <? $menu = getArrMenu( 3 );
                if ( ! empty( $menu ) ) {
                    ?>
                    <li class="list__item list__item_title">
                        <?= $menu['name'] ?>
                    </li>

                    <? /** @var WP_Post $item */
                    foreach ( $menu['items'] as $item ) {
                        ?>
                        <li class="list__item list__item_light <?= ( $item->url == home_url( $_SERVER['REQUEST_URI'] ) ) ? 'list__item_selected' : '' ?>">
                            <a href="<?= ( $item->url != home_url( $_SERVER['REQUEST_URI'] ) ) ? $item->url : 'javascript:void(0)' ?>"><?= $item->title ?></a>
                        </li>
                    <?
                    }
                }
                ?>
            </ul>
        </div>
        <div class="footer__menu-line__item fot_mob_new">
            <ul class="list list_vertical">
                <? $menu = getArrMenu( 4 );
                if ( ! empty( $menu ) ) {
                    ?>
                    <li class="list__item list__item_title">
                        <?= $menu['name'] ?>
                    </li>

                    <? /** @var WP_Post $item */
                    foreach ( $menu['items'] as $item ) {
                        ?>
                        <li class="list__item list__item_light <?= ( $item->url == home_url( $_SERVER['REQUEST_URI'] ) ) ? 'list__item_selected' : '' ?>">
                            <a href="<?= ( $item->url != home_url( $_SERVER['REQUEST_URI'] ) ) ? $item->url : 'javascript:void(0)' ?>"><?= $item->title ?></a>
                        </li>
                    <?
                    }
                }
                ?>
            </ul>
        </div>

        <div class="footer__menu-line__item">
            <ul class="list list_vertical">
                <li class="list__item list__item_title">Контактные данные:</li>

                <? foreach ( $info['phones'] as $item ) { ?>
                    <li class="list__item list__item_light">
                        <a href="<?= $item['hotline'] == 1 ? str_replace( '+7', '+8', $item['phone']->uri() ) : $item['phone']->uri() ?>" <?= ! empty( $item['onclick'] ) ? 'onclick="' . $item['onclick'] . '"' : ''; ?>><?= $item['phone']->{$item['type']}() ?></a>
                    </li>
                <? } ?>

                <? if ( ! empty( $info['email'] ) ) { ?>
                    <li class="list__item list__item_light">
                        <a href="mailto:<?= $info['email'] ?>"><?= $info['email'] ?></a>
                    </li>
                <? } ?>
                <br>
                <li class="list__item list__item_title">Часы работы:</li>

                <? if ( ! empty( $info['schedule']['days'] ) ) { ?>
                    <li class="list__item list__item_light">
                        <a href="javascript:void(0)"><?= $info['schedule']['days'] ?></a>
                    </li>
                <? } ?>
                <? if ( ! empty( $info['schedule']['hours'] ) ) { ?>
                    <li class="list__item list__item_light">
                        <a href="javascript:void(0)"><?= $info['schedule']['hours'] ?></a>
                    </li>
                <? } ?>

            </ul>
        </div>


        <div class="footer__menu-line__item footer__menu-line__item__mobile mobile_new">
            <ul class="list list_vertical">
                <? foreach ( $info['soc_list'] as $item ) { ?>
                    <li class="list__item list__item_light">
                        <a href="<?= $item['link'] ?>" target="_blank">
                            <img src="<?= $item['icon_dark']['url'] ?>" alt="<?= $item['icon_light']['title'] ?>">
                        </a>
                    </li>
                <? } ?>
            </ul>
        </div>

    </div>

    <div class="footer__copyright-line">
        <div class="footer__copyright-line__right-side">
            <a href="<?= get_privacy_policy_url() ?>">Политика конфиденциальности</a>
        </div>
        <div class="footer__copyright-line__left-side">© Все права защищены</div>
    </div>
</footer>

</body>
</html>

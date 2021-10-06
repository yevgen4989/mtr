<?
get_template_part('include/header', 'head');

if(!is_front_page()){
    //get_template_part( 'include/breadcrumbs' );
}

$info = get_fields('info');
?>


<header>
    <div class="header__main-line">
        <div class="header__main-line__logo">
            <?the_custom_logo()?>
            <div class="header__main-line__mobile-menu-line">
                <a href="#" class="header__main-line__mobile-menu-line__menu"></a>
            </div>
        </div>
        <div class="header__main-line__phones">
            <ul class="list list_horizontal">
                <?foreach ($info['phones'] as $item){?>
                    <li class="list__item">
                        <a href="<?= $item['hotline'] == 1 ? str_replace('+7', '+8', $item['phone']->uri()) : $item['phone']->uri() ?>" <?= !empty($item['onclick']) ? 'onclick="'.$item['onclick'].'"' : ''; ?>><?=$item['phone']->{$item['type']}()?></a>
                    </li>
                <?}?>
            </ul>
        </div>
        <div></div>
    </div>
    <div class="header__menu">
        <div class="header__menu__wrapper">
            <div class="header__menu__menu-block">
                <ul class="list list_horizontal">
                    <?$menu = getArrMenu(2);
                    if(!empty($menu)){
                        /** @var WP_Post $item */
                        foreach ($menu['items'] as $item){?>
                            <li class="list__item <?= ($item->url == home_url($_SERVER['REQUEST_URI'])) ? 'list__item_selected' : '' ?>">
                                <a href="<?= ($item->url != home_url($_SERVER['REQUEST_URI'])) ? $item->url : 'javascript:void(0)' ?>"><?=$item->title?></a>
                            </li>
                        <?}
                    }
                    ?>
                </ul>
            </div>
            <div class="header__menu__social-block">
                <ul class="list list_horizontal">
                    <?foreach ($info['soc_list'] as $item){?>
                        <li class="list__item">
                            <a href="<?=$item['link']?>" target="_blank">
                                <img src="<?=$item['icon_light']['url']?>" <?= $item['onclick'] ? 'onclick="'.$item['onclick'].'"' : '' ?> alt="<?=$item['icon_light']['title']?>">
                            </a>
                        </li>
                    <?}?>
                </ul>

                <ul class="list list_vertical header__menu__social-block__mobile-social">
                    <?foreach ($info['soc_list'] as $item){?>
                        <li class="list__item list__item_light">
                            <a href="<?=$item['link']?>" <?= $item['onclick'] ? 'onclick="'.$item['onclick'].'"' : '' ?> target="_blank"><?=$item['title']?></a>
                        </li>
                    <?}?>
                </ul>
            </div>
            <div class="header__menu__mobile-phones">
                <ul class="list list_horizontal">
                    <?foreach ($info['phones'] as $item){?>
                        <li class="list__item">
                            <a href="<?= $item['hotline'] == 1 ? str_replace('+7', '+8', $item['phone']->uri()) : $item['phone']->uri() ?>" <?= !empty($item['onclick']) ? 'onclick="'.$item['onclick'].'"' : ''; ?>><?=$item['phone']->{$item['type']}()?></a>
                        </li>
                    <?}?>
                </ul>
            </div>
        </div>
    </div>
</header>



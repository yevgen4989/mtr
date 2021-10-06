<?php
/**
 * Acf block: Контакты
 *
 * @var array $block
 */

$data = get_fields($block['id']);
$info = get_fields( 'info' );
?>

<script src="https://api-maps.yandex.ru/2.1/?apikey=328ea1f4-866e-48d1-b2e8-8b2f5a04a68f&lang=ru_RU"></script>

<div class="content-grid content-grid_nmargin">
    <div class="content-grid__item content-grid__item__once" itemscope itemtype="https://schema.org/Organization">
        <span style="display:none" itemprop="name">MTRGROUP</span>
        <h1 class="title title__size1"><?the_title()?></h1>

        <address itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
            <span itemprop="postalCode">
                <?=$data['block']['index']?>
            </span>,
            <?=$data['block']['country']?>,
            <span itemprop="addressLocality">
                <?=$data['block']['city']?>
            </span>,
            <span itemprop="streetAddress">
                <?=$data['block']['street']?>
            </span>
        </address>
        <div class="as-address">
            <ul class="list list_vertical">
                <?
                foreach ($data['block']['phones'] as $item){?>
                    <li class="list__item list__item_clear">
                        <a href="<?= $item['hotline'] == 1 ? str_replace('+7', '+8', $item['phone']->uri()) : $item['phone']->uri() ?>" <?= !empty($item['onclick']) ? 'onclick="'.$item['onclick'].'"' : ''; ?>><?=$item['phone']->{$item['type']}()?></a>
                    </li>
                <?}?>
            </ul>
        </div>
        <div class="as-address">
            <?if(!empty($data['block']['schedule'])){
                echo $data['block']['schedule'];
            }?>
        </div>
        <div class="as-address">
            <ul class="list list_vertical">
                <?foreach ($data['block']['soc_list'] as $item){?>
                    <li class="list__item list__item_clear">
                        <a href="<?=$item['link']?>" <?= isset($item['onclick']) && $item['onclick'] != '' ? 'onclick="'.$item['onclick'].'"' : '' ?> target="_blank"><?=$item['title']?></a>
                    </li>
                <?}?>
            </ul>
        </div>
        <? if ( ! empty( $info['email'] ) ) { ?>
            <div class="as-address">
                <a itemprop="email" href="mailto:<?= $info['email'] ?>"><?= $info['email'] ?></a>
            </div>
        <? } ?>
    </div>
    <div class="content-grid__item content-grid__item_nmargin content-grid__item_twice content-grid__item__full-height content-grid__item_clear contact-map"
         data-latitude="<?=$data['block']['map']['latitude']?>" data-longitude="<?=$data['block']['map']['longitude']?>"></div>
</div>

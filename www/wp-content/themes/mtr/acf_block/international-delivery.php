<?php
/**
 * Acf block: Международная доставка
 *
 * @var array $block
 */

$data = get_fields($block['id']);

$countries = get_posts( array(
    'numberposts' => -1,
    'orderby'     => 'id',
    'exclude'     => array(get_option('main_country')),
    'order'       => 'ASC',
    'post_type'   => 'countries',
    'post_parent' => 0,
) );


?>

<div class="content-grid content-grid_nmargin">
    <div class="content-grid__item content-grid__item_full">
        <h1 class="title title__size1 title__mbottom">
            Международная доставка
        </h1>
        <p class="h1 title title__size1 title__mbottom">Выберите страну или территорию
            <span class="title__explanation title__explanation_as-block">откуда вы будете доставлять груз в Россию</span>
        </p>
        <div class="form-inline form-inline_mbottom">
            <div class="form-group form-group_part-3">
                <input type="text" class="form-control form-group_white form-group_bordered" id="countrySearch" placeholder="Введите название страны, например: Италия">
            </div>
            <div class="form-group form-group_part-1">
                <button class="button button_as-input button_success" id="countryGoTo">Выбрать</button>
            </div>
        </div>
        <div class="panel panel_simple">
            <div class="panel__data panel__data_full">
                <?foreach ($countries as $country){
                    ?>
                    <a href="<?=get_the_permalink($country->ID)?>" class="button button_margin button_item_<?=$country->ID?>">
                        <img class="button__icon" src="<?=get_field('image', $country->ID)['url']?>" alt="<?=$country->post_title?>"> <span><?=$country->post_title?></span>
                    </a>
                    <?
                }?>
            </div>
        </div>
    </div>
</div>

<div class="window" id="deliveryRegionFail">
    <div class="window__body window__body_info">
        <a href="#" class="window__close"></a>
        <div class="window__body__title">Ошибка выбора</div>
        <div class="window__body__content">
            Доставка из указанного региона не осуществляется.
        </div>
    </div>
</div>

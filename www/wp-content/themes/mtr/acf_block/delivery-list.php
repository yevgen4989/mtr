<?php
/**
* Acf block: Расчет стоимости международной доставки
*
* @var array $block
*/

$data = get_fields($block['id']);

$countries = get_posts(array(
    'numberposts'	=> -1,
    'post_type'		=> 'countries',
    'post_parent' => 0,
    'exclude'     => array(get_option('main_country')),
    'meta_query'	=> [
        [
            'key' => 'in_main_page',
            'value' => '1',
        ]
    ],
));
?>


<div class="content-grid">
    <div class="content-grid__item content-grid__item_full">
        <div class="media-object media-object_center media-object_restrict-centered-block">
            <div class="media-object__title media-object__title_margin-big">
                <h1 class="title title__size1 title_no-margin">
                    Расчет стоимости международной доставки
                </h1>
                <p class="title title__size1 title_no-margin">
                    <span class="title__explanation">просто выберите страну отправления</span>
                </p>
            </div>
            <div class="media-object__content media-object__content_margin-big_middle">
                <?foreach ($countries as $country){
                    ?>
                    <a href="<?=get_the_permalink($country->ID)?>" class="button button_margin">
                        <img class="button__icon" src="<?=get_field('image', $country->ID)['url']?>" alt="<?=$country->post_title?>"> <span><?=$country->post_title?></span>
                    </a>
                    <?
                }?>
            </div>
            <div class="media-object__link">
                <a href="<?php echo get_permalink( get_option( 'page_delivery' ) ); ?>" class="arrowed-link arrowed-link__centered">
                    <div class="arrowed-link__title">Больше стран</div>
                    <div class="arrowed-link__arrow">
                        <img src="<?=get_template_directory_uri()?>/assets/img/arrow.svg" alt="arrow">
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

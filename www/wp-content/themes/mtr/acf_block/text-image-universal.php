<?php
/**
 * Acf block: Текстовый с картинкой
 *
 * @var array $block
 */
?>

<div class="media-object">
    <div class="media-object__title">
        <p class="h2 title title__size2 title_no-margin">
            <?=$block['data']['block_title']?>
        </p>
    </div>
    <div class="media-object__content">
        <?=$block['data']['block_svg_image'];?>
    </div>
    <?if(!empty($block['data']['block_page'])){
        $page = get_post($block['data']['block_page']);
        ?>
        <div class="media-object__link">
            <a href="<?the_permalink($page->ID)?>" class="arrowed-link">
                <div class="arrowed-link__title">
                    <?=$page->post_title?>
                </div>
                <div class="arrowed-link__arrow">
                    <img src="<?=get_template_directory_uri()?>/assets/img/arrow.svg" width="270" height="10" alt="arrow"></div>
            </a>
        </div>
    <?}?>
</div>

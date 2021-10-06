<?php get_header(); ?>

<div class="content">
    <?if(has_post_parent()){?>
        <div class="content-grid content-grid_nmargin">
            <div class="content-grid__item content-grid__item_twice content-grid__item__full-height">
                <h1 class="title title__size1">
                    <?the_title()?>
                </h1>
                <div class="panel panel_simple">
                    <div class="panel__data">

                    </div>
                    <div class="panel__helper text-right panel__helper_mobile">

                    </div>
                </div>
                <div class="edited-text">
                    <?the_content();?>
                    <div>
                        <a href="<?=get_the_permalink(get_post_parent()->ID)?>" class="arrowed-link">
                            <div class="arrowed-link__title">К разделу</div>
                            <div class="arrowed-link__arrow">
                                <img src="<?=get_template_directory_uri()?>/assets/img/arrow.svg" alt="arrow"></div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="content-grid__item content-grid__item_nmargin content-grid__item__once content-grid__item_no-background content-grid__item_clear">
                <div class="action-form">
                    <p class="h2 title title__size1">Нужно доставить грузы из-за границы в Россию? <span class="title__explanation">Поможет MTRGROUP</span></p>
                    <a href="<?=get_the_permalink(get_option('page_delivery'))?>" class="button button_big button_success">
                        Оформить заявку
                    </a>
                </div>
            </div>
        </div>
    <?}else{
       the_content();
    }?>
</div>

<?php get_footer(); ?>

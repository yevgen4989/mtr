<?php get_header();

?>

<div class="content">
    <div class="content-grid">
        <div class="content-grid__item content-grid__item_twice content-grid__item__full-height">
            <h1 class="title title__size1">
                <?= !empty(get_field('title', get_the_ID())) ? get_field('title', get_the_ID()) : the_title()?>
                <?= !empty(get_field('additional_to_title', get_the_ID())) ? '<span class="title__explanation">'.get_field('additional_to_title', get_the_ID()).'</span>' : ''?>
            </h1>
            <div class="panel panel_simple">
                <div class="panel__data">
                    <?
                    $arCategory = get_the_category(get_the_ID());
                    foreach ($arCategory as $item){?>
                        <span class="label"><?=$item->name?></span>
                    <?}?>
                </div>
                <div class="panel__helper text-right panel__helper_mobile">
                    <span class="label label_no-background label_clear label_text-gray"><?the_date('d.m.Y')?></span>
                </div>
            </div>
            <div class="edited-text">
                <?the_content();?>
            </div>
        </div>
        <div class="content-grid__item content-grid__item_nmargin content-grid__item__once content-grid__item_no-background content-grid__item_clear">
            <div class="action-form">
                <p class="title title__size1">Нужно доставить грузы из-за границы в Россию? <span class="title__explanation">Поможет MTRGROUP</span></p>
                <a href="<?=get_the_permalink(get_option('page_delivery'))?>" class="button button_big button_success">
                    Оформить заявку
                </a>
            </div>
        </div>
    </div>

    <?$posts = get_posts( array(
        'numberposts' => 6,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'post',
        'suppress_filters' => false,
        'exclude' => array(get_the_ID())
    ) );
    ?>

    <div class="content-grid content-grid_nmargin">
        <div class="content-grid__item content-grid__item_full">
            <div class="media-object">
                <div class="media-object__content">
                    <div class="announcement">
                        <?
                        foreach( $posts as $post ){
                            setup_postdata($post);?>

                            <a href="<?=get_the_permalink($post->ID)?>" class="announcement__item <?if(empty(get_post_thumbnail_id( $post->ID ))){?>announcement__item__bordered<?}?>">
                                <?if(!empty(get_post_thumbnail_id( $post->ID ))){?>
                                    <img src="<?=wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(800,400))[0]?>"
                                         class="announcement__item__background" width="800" height="400"
                                         alt="<?=$post->post_title?>">
                                    <div class="announcement__item__gradient"></div>
                                <?}?>
                                <div class="announcement__item__title">
                                    <p class="h3 title title__size3 title_white">
                                        <?= !empty(get_field('title',$post->ID)) ? get_field('title',$post->ID) : $post->post_title?>
                                        <?= !empty(get_field('additional_to_title',$post->ID)) ? '<span class="title__explanation">'.get_field('additional_to_title',$post->ID).'</span>' : ''?>
                                    </p>
                                </div>
                                <div class="announcement__item__labels">
                                    <?$arrCategory = $post->post_category;
                                    foreach ($arrCategory as $item){?>
                                        <span class="label"><?=get_the_category_by_ID($item);?></span>
                                    <?}?>
                                </div>
                            </a>

                        <?}
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <div class="media-object__link">
                    <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" class="arrowed-link">
                        <div class="arrowed-link__title">Вернуться в блог</div>
                        <div class="arrowed-link__arrow">
                            <img src="<?=get_template_directory_uri()?>/assets/img/arrow.svg" alt="arrow"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>

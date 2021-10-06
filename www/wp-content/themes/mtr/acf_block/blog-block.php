<?php
/**
 * Acf block: Блог
 */

$posts = get_posts( array(
    'numberposts' => 4,
    'orderby'     => 'date',
    'order'       => 'DESC',
    'post_type'   => 'post',
    'suppress_filters' => false,
) );
?>

<div class="content-grid content-grid_nmargin">
    <div class="content-grid__item content-grid__item_full">
        <div class="media-object">
            <div class="media-object__title">
                <h2 class="title title__size2 title_no-margin">
                    MTRGROUP блог
                </h2>
            </div>
            <div class="media-object__content">
                <div class="announcement">
                    <?
                    foreach( $posts as $post ){
                        setup_postdata($post);
                        ?>
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
                    <div class="arrowed-link__title">Все материалы</div>
                    <div class="arrowed-link__arrow">
                        <img src="<?=get_template_directory_uri()?>/assets/img/arrow.svg" alt="arrow"></div>
                </a>
            </div>
        </div>
    </div>
</div>

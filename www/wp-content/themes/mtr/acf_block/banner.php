<?php
/**
 * Acf block: Баннер
 *
 * @var array $block
 */
?>

<div class="announcement announcement_single">
    <div class="announcement__item">
        <?=wp_get_attachment_image( $block['data']['banner_bg'], array(1300, 660), false, array('class' => 'announcement__item__background')); ?>
        <img src="<?=get_template_directory_uri()?>/assets/img/logo_white.svg" class="logo-white" alt="mtrgroup">
    </div>
</div>

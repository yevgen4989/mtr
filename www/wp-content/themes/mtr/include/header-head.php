<?php
session_start();

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head itemscope itemtype="http://schema.org/WPHeader">
    <!--    <meta name="yandex-verification" content="a0b1c54e731a0693" />-->
    <!--    <meta name="yandex-verification" content="b5ecbab1c2539ca2" />-->

    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no">
    <meta name="robots" content="index, follow" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="<?=get_template_directory_uri()?>/assets/img/mstile-144x144.png">

    <link rel="apple-touch-icon" href="<?=get_template_directory_uri()?>/assets/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=get_template_directory_uri()?>/assets/img/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=get_template_directory_uri()?>/assets/img/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=get_template_directory_uri()?>/assets/img/apple-touch-icon-152x152.png">

    <link rel="icon" type="image/png" href="<?=get_template_directory_uri()?>/assets/img/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="<?=get_template_directory_uri()?>/assets/img/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?=get_template_directory_uri()?>/assets/img/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="<?=get_template_directory_uri()?>/assets/img/favicon-152x152.png" sizes="152x152">

    <script src="<?=get_template_directory_uri()?>/assets/js/script.js"></script>
    <link rel="stylesheet" href="<?=get_template_directory_uri()?>/assets/css/style.css" type="text/css"/>

<!--    <link rel="preload" href="--><?//=get_template_directory_uri()?><!--/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin>-->

    <?php wp_head(); ?>

    <style>
        .lazy-bg{
            background-image: none!important;
            background-color: #FFF!important;
        }
    </style>
</head>
